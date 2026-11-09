# Checkpoint 07: Filtering input

## Goal

Treat every value that arrives as untrusted: read it through PHP's filter functions, and escape it before it goes back into a page.

## Concepts

- Any data from outside your code is tainted until you have checked it. That includes forms, query strings, cookies, other websites' APIs and even your own database. Assume that 100% of it could be an attack.
- PHP has two filter functions:
  - `filter_input(INPUT_POST, 'email')` reads one value from the request. The first argument says where from: `INPUT_GET`, `INPUT_POST`, `INPUT_COOKIE`, `INPUT_SERVER` or `INPUT_ENV`. It returns `null` if the value was not sent, so you no longer need `isset($_POST[...])` first.
  - `filter_var($value, ...)` filters a value you already have.
- A filter is either a validation filter or a sanitisation filter.
  - A validation filter checks a value against a pattern, and returns the value or `false`. There are filters for booleans, domains, emails, floats, ints, IP and MAC addresses, regular expressions and URLs.
  - A sanitisation filter removes unwanted characters, to make a value safe. It changes the value instead of rejecting it.
- With no filter named, `filter_input()` uses `FILTER_DEFAULT`, which only reads the value. `FILTER_REQUIRE_ARRAY` as the fourth argument says the value must be an array. Anything else gives `false`, which is why the code checks `is_array()`, not `isset()`.
- Validating input is half the job. The other half is escaping output. If a visitor types `<script>alert(1)</script>` as a comment, and you echo it unchanged, the browser runs it. This is called cross-site scripting, or XSS. `htmlspecialchars()` turns `<`, `>`, `&` and quotes into HTML entities such as `&lt;`, so the browser shows the characters instead of running them. Use it on every value a visitor sent, every time you print one into HTML.
- The slides read the method with `filter_var($_SERVER['REQUEST_METHOD'], FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE)`. `FILTER_UNSAFE_RAW` does nothing to the value, so this unit keeps `$_SERVER['REQUEST_METHOD'] === 'POST'`.

## Before you start

You need the contact form from checkpoint 06. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/06-client-validation/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

The code below includes the check from the checkpoint 05 "your turn", that every reason is one of the four in `$reasons`. Start the server in `contact-form/`.

## Steps

1. Follow along: see the problem first. Fill in the form correctly, but type this as the comment:

   ```html
   <script>alert('Hacked!')</script>
   ```

   Send. *Check: an alert box saying `Hacked!`.* The comment was printed into the page as HTML, and the browser ran it. A real attacker would send a visitor's cookies somewhere instead.

2. Follow along: replace the whole PHP block at the top of `index.php`, from `<?php` to `?>`, with:

   ```php
   <?php
   $email = '';
   $comments = '';
   $inputReason = [];
   $inputContactMethod = [];
   $newsletter = 'no';
   $maxSize = ini_get('upload_max_filesize');
   $errors = [];
   $reasons = ['Technical Support', 'Sales', 'Bug Report', 'General'];

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // Validate email
       $inputEmail = filter_input(INPUT_POST, 'email');
       if (isset($inputEmail) && $inputEmail !== '') {
           if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
               $email = $inputEmail;
           } else {
               $errors[] = 'E-mail address is invalid.';
           }
       } else {
           $errors[] = 'E-mail address is missing.';
       }

       // Validate comments
       $inputComments = filter_input(INPUT_POST, 'comments');
       if (isset($inputComments) && $inputComments !== '') {
           $comments = $inputComments;
       } else {
           $errors[] = 'Comments are missing.';
       }

       // Validate reason for contact: anything but an array becomes false
       $inputReason = filter_input(INPUT_POST, 'reason', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
       if (!is_array($inputReason) || count($inputReason) === 0) {
           $inputReason = [];
           $errors[] = 'Please choose at least one reason for contacting us.';
       } elseif (count(array_diff($inputReason, $reasons)) > 0) {
           $errors[] = 'Please choose only reasons from the list.';
       }

       // Validate contact method
       $inputContactMethod = filter_input(INPUT_POST, 'contact_method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
       if (!is_array($inputContactMethod) || count($inputContactMethod) === 0) {
           $inputContactMethod = [];
           $errors[] = 'Please specify a method by which we can get in touch.';
       }

       // Newsletter: anything but 'yes' counts as no
       $newsletter = filter_input(INPUT_POST, 'newsletter') === 'yes' ? 'yes' : 'no';

       // Validate file size: PHP refuses a file over upload_max_filesize and reports it here
       if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_INI_SIZE) {
           $errors[] = "The file you uploaded is too big. Max size: $maxSize";
       }

       if (isset($_FILES['uploadedFile']) && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) {
           $uploadedFile = $_FILES['uploadedFile']['tmp_name'];

           // Validate file type
           $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
           $mimeType = finfo_file($fileInfo, $uploadedFile);
           $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];
           if (!array_key_exists($mimeType, $allowedTypes)) {
               $errors[] = 'The file you uploaded is not an accepted type (accepted: PNG, JPG, GIF).';
           }

           if (count($errors) === 0) {
               $uploadFolder = 'uploads/';
               $uniqueId = time() . uniqid(rand());
               $origName = pathinfo($_FILES['uploadedFile']['name'], PATHINFO_FILENAME);
               $ext = $allowedTypes[$mimeType];
               $destFile = $uploadFolder . $origName . "_$uniqueId." . $ext;

               if (move_uploaded_file($uploadedFile, $destFile)) {
                   echo '<p>File uploaded successfully.</p>';
               } else {
                   echo '<p>There was an error uploading the file.</p>';
               }
           }
       }

       if (count($errors) === 0) {
           // Everything the visitor typed is escaped before it goes into the page
           printf('<p>Received email: %s and comments %s</p>', htmlspecialchars($email), htmlspecialchars($comments));
           echo '<p>Reasons for contacting us:</p>';
           echo '<ul>';
           foreach ($inputReason as $reason) {
               echo '<li>' . htmlspecialchars($reason) . '</li>';
           }
           echo '</ul>';
           printf('<p>We can contact you by %s</p>', htmlspecialchars(join(', ', $inputContactMethod)));
           printf('<p>You %s want to subscribe to our newsletter</p>', $newsletter === 'yes' ? 'do' : 'do not');
       }
   }
   ?>
   ```

   What changed:
   - Every value is read with `filter_input()`, and nothing reads `$_POST` any more.
   - `$inputReason`, `$inputContactMethod` and `$newsletter` start with safe defaults at the top, so the form can use them on a `GET` request too.
   - `$newsletter` can only ever be `'yes'` or `'no'`.
   - The received values are printed through `htmlspecialchars()`.

3. Follow along: the form still reads `$_POST` to stay sticky. Replace the email line, the select, the checkboxes, the radio buttons and the comments with:

   ```php
   <label for="email" style="display: inline-block; width: 100px;">Email</label>
   <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
   <p class="validation_message">Please enter a valid email address.</p>
   <br>
   <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
   <select name="reason[]" id="reason" multiple>
       <option <?= in_array('Technical Support', $inputReason) ? 'selected' : '' ?>>Technical Support</option>
       <option <?= in_array('Sales', $inputReason) ? 'selected' : '' ?>>Sales</option>
       <option <?= in_array('Bug Report', $inputReason) ? 'selected' : '' ?>>Bug Report</option>
       <option <?= in_array('General', $inputReason) ? 'selected' : '' ?>>General</option>
   </select>
   <p class="validation_message">Please choose one or more reasons for your query.</p>
   <br>
   <label style="display: inline-block; width: 100px;">Contact me by</label>
   <input type="checkbox" name="contact_method[]" value="email" <?= in_array('email', $inputContactMethod) ? 'checked' : '' ?>>Email
   <input type="checkbox" name="contact_method[]" value="phone" <?= in_array('phone', $inputContactMethod) ? 'checked' : '' ?>>Phone
   <input type="checkbox" name="contact_method[]" value="sms" <?= in_array('sms', $inputContactMethod) ? 'checked' : '' ?>>SMS
   <input type="checkbox" name="contact_method[]" value="whatsapp" <?= in_array('whatsapp', $inputContactMethod) ? 'checked' : '' ?>>WhatsApp
   <p class="validation_message">Please tell us how you'd like us to get back to you.</p>
   <br>
   <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
   <input type="radio" name="newsletter" value="yes" <?= $newsletter === 'yes' ? 'checked' : '' ?>>Yes
   <input type="radio" name="newsletter" value="no" <?= $newsletter === 'no' ? 'checked' : '' ?>>No
   <br><br>
   <label for="comments" style="display: inline-block; width: 100px;">Comments</label>
   <textarea id="comments" name="comments"><?= htmlspecialchars($comments) ?></textarea>
   <p class="validation_message">Please enter your query.</p>
   <br>
   ```

4. Follow along: repeat step 1.

   *Check: no alert. The page shows `<script>alert('Hacked!')</script>` as plain text.* View the page source (Ctrl+U, or Cmd+Option+U on a Mac). *Check: the comment appears as `&lt;script&gt;...`.*

   Now leave out the reason, so the form comes back with an error. *Check: the comment is back in the box, as text, and no alert.*

5. Follow along: send a reason that is not an array, with `curl`:

   ```sh
   curl -s http://localhost:8000/index.php -F email=a@b.com -F comments=hi -F reason=Sales -F "contact_method[]=sms"
   ```

   *Check: `Please choose at least one reason for contacting us.`, not a PHP error.* `FILTER_REQUIRE_ARRAY` turned the single value into `false`.

6. Your turn: the checkpoint 01 `product.php` prints `$_GET['id']` straight into the page. Copy it into `contact-form/`, and visit `http://localhost:8000/product.php?id=<b>23</b>`. *Check: the 23 is bold.* Change it to read both parameters with `filter_input(INPUT_GET, ...)`, and to validate `id` as a whole number with `FILTER_VALIDATE_INT`. Show `Product ID: invalid` when it is not one. Escape `sort` when you print it. Delete `product.php` from `contact-form/` when you are done.

## Done when

- [ ] Nothing in the contact form reads `$_POST` directly.
- [ ] A `<script>` typed into any field is shown as text, never run.
- [ ] A reason sent as a single value gives an error message, not a PHP error.
- [ ] You can say the difference between validating input and escaping output.

## Previous / Next

Previous: [06 Client-side validation](../06-client-validation/README.md). Next: [08 Sessions and cookies](../08-sessions-cookies/README.md).
