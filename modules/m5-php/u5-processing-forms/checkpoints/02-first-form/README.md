# Checkpoint 02: A first form

## Goal

Build a contact form that sends its data back to the same page, read that data in PHP, and keep it in the form after sending.

## Concepts

- A form's `method` says how the browser sends it. With `method="POST"`, the values travel in the request body, not in the address.
- A form's `action` says where to send it. Without an `action`, the browser sends it to the page it is on. A page that both shows a form and handles it is called a self-processing page.
- The body has two possible formats. `application/x-www-form-urlencoded` is the default: `email=keith%40icemalta.com&comments=Hello`, with `&` between fields. `multipart/form-data` puts each field in its own section, separated by a boundary string. It is needed for file uploads, in checkpoint 04. PHP reads both formats into `$_POST`.
- Each field's `name` becomes a key in `$_POST`. `<input name="email">` arrives as `$_POST['email']`.
- `$_SERVER['REQUEST_METHOD']` is `'GET'` when the page is first opened and `'POST'` when the form is sent. Checking it tells you whether there is form data to read.
- After sending, the browser draws the page again from scratch, so the form comes back empty. Printing the received values back into the fields keeps them. This is a sticky form. It lets a visitor fix a mistake without typing everything again.
- A request is idempotent when sending it twice has the same effect as sending it once. `GET` should be: it only reads. `POST` is not: it is meant to create something. That is why refreshing a page after a `POST` makes the browser ask before sending the form again.

## Before you start

You work in the unit's [`contact-form/`](../../contact-form) folder for checkpoints 02 to 08. Start the server there:

```sh
cd modules/m5-php/u5-processing-forms/contact-form
php -S localhost:8000
```

## Steps

1. Follow along: replace the contents of `index.php` with:

   ```php
   <?php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $email = $_POST['email'];
       $comments = $_POST['comments'];
       printf('<p>Received email: %s and comments %s</p>', $email, $comments);
   }
   ?>
   <!DOCTYPE html>
   <html lang="en">

   <head>
       <title>Using Forms</title>
   </head>

   <body>
       <h3>Contact Us</h3>
       <form name="contact_form" method="POST" action="index.php">
           <label for="email" style="display: inline-block; width: 100px;">Email</label>
           <input type="email" id="email" name="email"><br><br>
           <label for="comments" style="display: inline-block; width: 100px;">Comments</label>
           <textarea id="comments" name="comments"></textarea><br>
           <label style="display: inline-block; width: 100px;"></label>
           <input type="submit" value="Send">
       </form>
   </body>

   </html>
   ```

   Visit <http://localhost:8000>. Fill in both fields and click Send. *Check: `Received email: ... and comments ...` above the form, which is now empty.*

2. Follow along: look at what was sent. Open the Network tab, send the form again, and click the `index.php` request.

   *Check: the method is `POST`. Under Payload (Request in Firefox), you see `email` and `comments` with your values. The address bar has no query string.*

3. Follow along: refresh the page with F5.

   *Check: the browser asks whether to send the form again.* Click Cancel. Because `POST` is not idempotent, the browser will not repeat it without asking. You will avoid this warning with JavaScript requests later in the module.

4. Follow along: make the form sticky. Add two lines at the very top of the PHP block, so it starts:

   ```php
   <?php
   $email = '';
   $comments = '';

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   ```

   Then print the values back into the fields:

   ```php
   <input type="email" id="email" name="email" value="<?= $email ?>"><br><br>
   ```

   ```php
   <textarea id="comments" name="comments"><?= $comments ?></textarea><br>
   ```

   The two empty strings are needed because on a `GET` request the `if` does not run, and the fields still print `$email` and `$comments`.

   Send the form. *Check: your values are still in the fields after sending.*

5. Your turn: change the form's `method` to `GET` and send it.
   - *Check: the values now appear in the address bar as a query string, and nothing is printed above the form.*
   - Change the `if` to check for `'GET'` and read from `$_GET` instead, and send again. *Check: the message is back.* What happens now when you first open the page, before sending anything?
   - Put the form back to `POST` and `$_POST` before you go on.

## Done when

- [ ] The form sends to itself with `POST` and prints both values.
- [ ] The values stay in the form after sending.
- [ ] You found the form data in the Network tab.
- [ ] You can say why the browser asks before refreshing after a `POST`, but not after a `GET`.

## Previous / Next

Previous: [01 HTTP requests](../01-http/README.md). Next: [03 Multi-valued fields](../03-multi-valued/README.md).
