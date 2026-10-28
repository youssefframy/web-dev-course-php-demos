# Checkpoint 01: Your first PHP page

## Goal

Run PHP's built-in web server, add PHP to an HTML page, and see that the browser only ever receives HTML.

## Concepts

A browser understands HTML, CSS and JavaScript, and nothing else. When it asks for a `.php` file, the web server first hands the file to PHP. PHP runs the code inside the file and sends back plain HTML.

- PHP code goes between `<?php` and `?>`. Everything outside those tags is sent to the browser unchanged.
- Each PHP statement ends with a semicolon `;`.
- A variable stores a value. Its name starts with `$`, for example `$version = 1.1;`.
- `echo` sends text to the page: `<?php echo "<p>Hello</p>"; ?>`.
- `<?= $version ?>` is a shortcut for `<?php echo $version; ?>`. Use it to drop a single value into your HTML.
- `phpinfo()` builds a page that describes your PHP installation.

It is common to put PHP that does not display anything at the very top of the file, before `<!DOCTYPE html>`.

## Before you start

Open a terminal and start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u1-welcome-to-php/checkpoints/01-first-php-page
php -S localhost:8000
```

Leave it running. Open a second terminal (the + button in the terminal panel) if you need one.

## Steps

1. Follow along: create a file called `test.php` in this folder, containing:

   ```php
   <?php
   phpinfo();
   ```

   Visit <http://localhost:8000/test.php>. Find the PHP version at the top of the page.

2. Follow along: open `mypage.php`. It is plain HTML so far. Visit <http://localhost:8000/mypage.php> to see it.

3. Follow along: add three pieces of PHP to `mypage.php`.
   - At the very top of the file, before `<!DOCTYPE html>`:

     ```php
     <?php
         $version = 1.1;
     ?>
     ```

   - After the normal paragraph:

     ```php
     <?php echo "<p>This paragraph comes from PHP</p>"; ?>
     <p>Document version <?= $version ?></p>
     ```

   Save and refresh. *Check: three paragraphs, the last one says "Document version 1.1".*

4. View the page source in the browser (Ctrl+U, or Cmd+Option+U on a Mac). Look for `<?php`. *Check: it is not there. The browser only received HTML.*

5. Change `$version` to `1.2`, save and refresh. The page updates without restarting the server.

6. Your turn: `profile.php` is a profile card with placeholders in square brackets.
   - At the top of the file, create four variables: `$name`, `$hometown`, `$favouriteFood` and `$luckyNumber`. Put text in double quotes, for example `"Valletta"`. A number does not need quotes, for example `7`.
   - Replace each placeholder with a `<?= ?>` tag that shows the matching variable.
   - Under the list, use the long form, `<?php echo ... ?>`, to add this paragraph: `<p class='note'>This card was built by PHP.</p>`. Keep the single quotes around `note`: the whole paragraph is already inside double quotes.

   *Check: no square brackets left on the page, and the purple note appears at the bottom.*

7. Change two of your variables, save and refresh. Only the top of the file changed, but the card shows the new values.

8. Stop the server with Ctrl+C.

## Done when

- [ ] `test.php` shows the PHP information page.
- [ ] `mypage.php` shows "This paragraph comes from PHP" and the document version.
- [ ] `profile.php` shows your own details, all coming from variables.
- [ ] You have checked the page source and found no PHP code in it.
- [ ] You can say what `<?= $name ?>` is short for.

## Previous / Next

Previous: [Unit 1 overview](../../README.md). Next: [02 Including sections](../02-includes/README.md).
