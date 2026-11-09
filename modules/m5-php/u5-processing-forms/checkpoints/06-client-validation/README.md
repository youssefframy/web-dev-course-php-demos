# Checkpoint 06: Client-side validation

## Goal

Check the form in the browser before it is sent, and show a message under each field that needs fixing.

## Concepts

- Client-side validation saves the visitor a round trip: mistakes show at once, without sending the form. It does not protect the server. JavaScript can be turned off, and the page can be edited in the developer tools. Your PHP checks from checkpoint 05 stay exactly as they are.
- Browsers already check some HTML attributes, such as `type="email"` and `required`, with their own pop-up messages. `novalidate` on the `<form>` turns that off, so your own script and messages are the only ones shown.
- The `submit` event fires when the form is about to be sent. A listener that calls `evt.preventDefault()` stops it from being sent.
- A regular expression is a pattern for text. `/^[^\s@]+@[^\s@]+\.[^\s@]+$/` means: some characters without spaces or `@`, then `@`, then more of them, a dot, and more. It catches most typing mistakes. The full rules for email addresses are far more complicated, which is why the server's `FILTER_VALIDATE_EMAIL` has the final say. The slides take a longer pattern from a gist. This shorter one is enough here.
- Each field is followed by a hidden `<p class="validation_message">`. The script shows the message that follows an invalid field, and outlines the field in red with the class `validation_invalid`. Every attempt starts by hiding all messages and outlines again, so fixed fields lose them.

## Before you start

You need the contact form from checkpoint 05. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u5-processing-forms/checkpoints/05-server-validation/contact-form/. modules/m5-php/u5-processing-forms/contact-form/
```

Start the server in `contact-form/`.

## Steps

1. Follow along: change the form's opening tag, so the script can find it and the browser leaves the checking to you:

   ```html
   <form enctype="multipart/form-data" name="contact_form" method="POST" action="index.php" id="contactForm" novalidate>
   ```

2. Follow along: add a hidden message straight after each field that you will check. The email line becomes:

   ```php
   <input type="email" id="email" name="email" value="<?= $email ?>">
   <p class="validation_message">Please enter a valid email address.</p>
   <br>
   ```

   After the `</select>`:

   ```html
   </select>
   <p class="validation_message">Please choose one or more reasons for your query.</p>
   <br>
   ```

   After the WhatsApp checkbox:

   ```html
   WhatsApp
   <p class="validation_message">Please tell us how you'd like us to get back to you.</p>
   <br>
   ```

   And after the comments:

   ```php
   <textarea id="comments" name="comments"><?= $comments ?></textarea>
   <p class="validation_message">Please enter your query.</p>
   <br>
   ```

   Each of these replaces a `<br><br>` that followed the field.

3. Follow along: in `<head>`, under the `<title>`, add the two styles:

   ```html
   <style>
       .validation_message {
           font-size: small;
           font-style: italic;
           color: red;
           margin: 0;
           display: none;
       }

       .validation_invalid {
           outline: 1px solid red;
       }
   </style>
   ```

   Refresh. *Check: the page looks the same as before. The messages are there, but hidden.*

4. Follow along: just before `</body>`, add the script:

   ```html
   <script>
       document.getElementById('contactForm').addEventListener('submit', validateForm);

       function validateForm(evt) {
           let containsErrors = false;

           // Reset validation from the last attempt
           for (const message of document.querySelectorAll('.validation_message')) {
               message.style.display = 'none';
           }
           for (const field of document.querySelectorAll('.validation_invalid')) {
               field.classList.remove('validation_invalid');
           }

           // Email
           const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
           const email = document.getElementById('email');
           if (!re.test(email.value)) {
               containsErrors = true;
               showError(email);
           }

           // Query Type
           const reason = document.getElementById('reason');
           if (reason.selectedOptions.length === 0) {
               containsErrors = true;
               showError(reason);
           }

           // Contact Method
           const checkboxes = document.querySelectorAll('input[name="contact_method[]"]');
           const checkedOne = Array.from(checkboxes).some(checkbox => checkbox.checked);
           if (!checkedOne) {
               containsErrors = true;
               for (const checkbox of checkboxes) {
                   checkbox.classList.add('validation_invalid');
               }
               getNextSibling(checkboxes[0], '.validation_message').style.display = 'block';
           }

           // Comments
           const comments = document.getElementById('comments');
           if (comments.value.trim() === '') {
               containsErrors = true;
               showError(comments);
           }

           // Stop the form from being sent if anything is wrong
           if (containsErrors) {
               evt.preventDefault();
           }
       }

       // Outline a field in red, and show the message that follows it
       function showError(field) {
           field.classList.add('validation_invalid');
           getNextSibling(field, '.validation_message').style.display = 'block';
       }

       // Find the first element after elem that matches selector
       function getNextSibling(elem, selector) {
           let sibling = elem.nextElementSibling;
           while (sibling) {
               if (sibling.matches(selector)) {
                   return sibling;
               }
               sibling = sibling.nextElementSibling;
           }
           return null;
       }
   </script>
   ```

   The slides declare `contains_errors` and `checkbox` without `let` or `const`, which makes them global variables by accident. Here every variable is declared. `selectedOptions` lists the chosen options of a `<select>`, and `some()` is true when at least one checkbox is ticked.

5. Follow along: send the empty form.

   *Check: four red messages, the fields outlined, and in the Network tab no new request to `index.php`. The form was never sent.* Fill in the email and choose a reason, and send again. *Check: those two messages disappear, the other two stay.* Fill in the rest and send. *Check: the form is sent and PHP shows what it received.*

6. Follow along: prove the server still protects itself. Open the developer tools, press Ctrl+Shift+P (Cmd+Shift+P on a Mac), type `Disable JavaScript`, and press Enter. Send the empty form.

   *Check: no red messages under the fields, but the red box from PHP lists every problem.* Run `Enable JavaScript` the same way.

7. Your turn: the server rejects an email address that the script accepts, `a@b.c..`, for example. Find another one: think of addresses with a dot in the wrong place. Type it in, send, and check that the PHP error box catches it. Then explain in one sentence why that is fine.

## Done when

- [ ] An empty form shows four messages and is not sent.
- [ ] Fixed fields lose their message on the next attempt.
- [ ] With JavaScript off, the PHP checks still catch every problem.
- [ ] You can say why `novalidate` is on the form.

## Previous / Next

Previous: [05 Server-side validation](../05-server-validation/README.md). Next: [07 Filtering input](../07-filtering/README.md).
