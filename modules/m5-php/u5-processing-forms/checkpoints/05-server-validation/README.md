# Checkpoint 05: Server-side validation

## Goal

Check every value on the server, tell the visitor exactly what to fix, and give them back the form with everything they chose still in place.

## Concepts

- Validation means checking input before you use it. It happens in two places, and you need both:
  - Client-side validation runs in the browser, with HTML attributes and JavaScript. It is for the visitor's convenience: they see mistakes before sending. Checkpoint 06 adds it.
  - Server-side validation runs in PHP. It protects the server. The visitor can turn JavaScript off, edit the HTML, or send a request without a browser at all, so the server must never trust what arrives.
- Collect every problem in an array, `$errors`, instead of stopping at the first one. Then the visitor can fix everything in one go.
- `filter_var($value, FILTER_VALIDATE_EMAIL)` returns the value if it looks like an email address, and `false` if not. `filter_var()` is PHP's built-in function for checking and cleaning values. Checkpoint 07 covers it properly.
- When a file is bigger than `upload_max_filesize`, PHP never saves it. Instead, `$_FILES['uploadedFile']['error']` holds the constant `UPLOAD_ERR_INI_SIZE`. That is the check to use. The slides compare `$_FILES[...]['size']` with `$maxSize`, but `$maxSize` is the text `'2M'`, not a number of bytes, so that comparison never catches anything.
- A sticky form keeps every kind of field, not only text. A `<select>` option needs `selected`, and a checkbox or radio button needs `checked`, when its value was among those sent. `in_array($value, $array)` tells you whether it was.

## Before you start

You need the contact form from checkpoint 04. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/04-file-uploads/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

Start the server in `contact-form/`.

## Steps

1. Follow along: send the form with every field empty. *Check: the page happily says it received an empty email and empty comments.* There is work to do.

2. Follow along: replace the whole PHP block at the top of `index.php`, from `<?php` to `?>`, with:

   ```php
   <?php
   $email = '';
   $comments = '';
   $maxSize = ini_get('upload_max_filesize');
   $errors = [];

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // Validate email
       if (isset($_POST['email']) && $_POST['email'] !== '') {
           if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
               $email = $_POST['email'];
           } else {
               $errors[] = 'E-mail address is invalid.';
           }
       } else {
           $errors[] = 'E-mail address is missing.';
       }

       // Validate comments
       if (isset($_POST['comments']) && $_POST['comments'] !== '') {
           $comments = $_POST['comments'];
       } else {
           $errors[] = 'Comments are missing.';
       }

       // Validate reason for contact
       if (!isset($_POST['reason']) || count($_POST['reason']) === 0) {
           $errors[] = 'Please choose at least one reason for contacting us.';
       }

       // Validate contact method
       if (!isset($_POST['contact_method']) || count($_POST['contact_method']) === 0) {
           $errors[] = 'Please specify a method by which we can get in touch.';
       }

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
           printf('<p>Received email: %s and comments %s</p>', $email, $comments);
           echo '<p>Reasons for contacting us:</p>';
           echo '<ul>';
           foreach ($_POST['reason'] as $reason) {
               echo "<li>$reason</li>";
           }
           echo '</ul>';
           printf('<p>We can contact you by %s</p>', join(', ', $_POST['contact_method']));
           printf('<p>You %s want to subscribe to our newsletter</p>', $_POST['newsletter'] === 'yes' ? 'do' : 'do not');
       }
   }
   ?>
   ```

   Read it from the top:
   - Each field gets its own check. A missing or empty value adds a message to `$errors`, instead of being used.
   - `$email` is only set when the address is valid, so an invalid one is not printed back into the form.
   - The file is only moved when there are no errors at all. There is no point keeping a screenshot for a form that will be sent again.
   - The received values are only shown when `$errors` is still empty.

3. Follow along: show the errors. Straight after `<body>`, add:

   ```php
   <?php if (count($errors) > 0) { ?>
       <div id="error_alert" style="background-color: #e3767d; border-radius: 5px; max-width: 400px; padding: 5px;">
           <ul>
               <?php
               foreach ($errors as $error) {
                   echo "<li>$error</li>";
               }
               ?>
           </ul>
       </div>
   <?php } ?>
   ```

   Send the empty form again. *Check: a red box listing four problems: email, comments, reason and contact method.* Fill in only the email with `nope` and send. *Check: `E-mail address is invalid.` instead of `missing`.*

4. Follow along: fill in everything except the Query Type, tick SMS and WhatsApp, choose Yes, and send.

   *Check: the error about the reason, and the comments are still there, but SMS, WhatsApp and Yes are lost.* The form is not sticky enough.

5. Follow along: make the select, checkboxes and radio buttons sticky. Replace them with:

   ```php
   <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
   <select name="reason[]" id="reason" multiple>
       <option <?= isset($_POST['reason']) && in_array('Technical Support', $_POST['reason']) ? 'selected' : '' ?>>Technical Support</option>
       <option <?= isset($_POST['reason']) && in_array('Sales', $_POST['reason']) ? 'selected' : '' ?>>Sales</option>
       <option <?= isset($_POST['reason']) && in_array('Bug Report', $_POST['reason']) ? 'selected' : '' ?>>Bug Report</option>
       <option <?= isset($_POST['reason']) && in_array('General', $_POST['reason']) ? 'selected' : '' ?>>General</option>
   </select><br><br>
   <label style="display: inline-block; width: 100px;">Contact me by</label>
   <input type="checkbox" name="contact_method[]" value="email" <?= isset($_POST['contact_method']) && in_array('email', $_POST['contact_method']) ? 'checked' : '' ?>>Email
   <input type="checkbox" name="contact_method[]" value="phone" <?= isset($_POST['contact_method']) && in_array('phone', $_POST['contact_method']) ? 'checked' : '' ?>>Phone
   <input type="checkbox" name="contact_method[]" value="sms" <?= isset($_POST['contact_method']) && in_array('sms', $_POST['contact_method']) ? 'checked' : '' ?>>SMS
   <input type="checkbox" name="contact_method[]" value="whatsapp" <?= isset($_POST['contact_method']) && in_array('whatsapp', $_POST['contact_method']) ? 'checked' : '' ?>>WhatsApp
   <br><br>
   <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
   <input type="radio" name="newsletter" value="yes" <?= isset($_POST['newsletter']) && $_POST['newsletter'] === 'yes' ? 'checked' : '' ?>>Yes
   <input type="radio" name="newsletter" value="no" <?= !isset($_POST['newsletter']) || $_POST['newsletter'] === 'no' ? 'checked' : '' ?>>No
   <br><br>
   ```

   On the first visit `$_POST` is empty, so `isset()` is false and nothing is selected, except the No button. Its condition starts with `!isset(...)`, so it is the default.

   Repeat step 4. *Check: SMS, WhatsApp and Yes are still chosen after the error.*

6. Follow along: send a valid form with an image over 2MB. *Check: `The file you uploaded is too big. Max size: 2M`.* Then send the `fake.png` from checkpoint 04. *Check: `The file you uploaded is not an accepted type (accepted: PNG, JPG, GIF).`, with the rest of the form kept.*

7. Your turn: skip the browser, and its checks, completely. In a second terminal, send a form with `curl`:

   ```sh
   curl -s http://localhost:8000/index.php -F email=not-an-email -F "reason[]=Hacking" -F newsletter=maybe
   ```

   `-F` sends one form field, the way a browser does. *Check: the errors for the email, the comments and the contact method appear in the HTML that comes back.*
   - `Hacking` is not one of the four options, but no error mentions it. Add a check that every received reason is one of the four. Hint: put the four in an array, `$reasons`, and use `in_array()` in a loop, or `array_diff()`.
   - `maybe` is not `yes` or `no`. What does the page say about the newsletter if you fix the other fields and send `maybe`? Is that acceptable?

## Done when

- [ ] An empty form lists every problem at once, with no PHP warnings.
- [ ] Every field keeps its value after an error.
- [ ] A too-large or wrong-type file gets its own message.
- [ ] Nothing is shown or saved while there are errors.
- [ ] A reason that is not one of the four options is rejected.
- [ ] You can say why the server must validate even when the browser already does.

## Previous / Next

Previous: [04 File uploads](../04-file-uploads/README.md). Next: [06 Client-side validation](../06-client-validation/README.md).
