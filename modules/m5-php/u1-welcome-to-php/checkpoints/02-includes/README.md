# Checkpoint 02: Including sections

## Goal

Give every page of the Intro Website the same `<head>` and menu, written once and included with PHP.

## Concepts

Most pages on a site share parts: the `<head>`, the menu, the footer. Copying them into every page means changing every page whenever they change. Instead, put each shared part in its own file and include it.

```php
<?php include 'includes/menu.php' ?>
```

PHP replaces that line with the contents of `includes/menu.php` before it sends the page. The browser receives one complete HTML page and never sees the `include`.

## Before you start

This checkpoint starts the Intro Website. Its files are in [`intro-website/`](../../intro-website):

| File | What it is |
|---|---|
| `css/bootstrap.min.css` | Bootstrap's CSS, which styles the site. |
| `js/bootstrap.bundle.min.js` | Bootstrap's JavaScript, used by the menu on small screens. |
| `includes/head.php` | The `<head>` section, which loads Bootstrap. |
| `includes/menu.php` | The menu bar. |
| `index.php` | The home page. |
| `aboutphp.php` | A page about PHP. |
| `aboutmariadb.php` | A page about MariaDB. |

Start the server in that folder:

```sh
cd modules/m5-php/u1-welcome-to-php/intro-website
php -S localhost:8000
```

## Steps

1. Visit <http://localhost:8000>. The page has no styling and no menu, because `index.php` has no `<head>` and nothing includes the menu yet.

2. Open `includes/head.php` and `includes/menu.php`. They are ordinary HTML, saved as `.php` files.

3. Follow along: in `index.php`, include both files. The head goes between `<html>` and `<body>`, and the menu at the start of the body:

   ```php
   <!doctype html>
   <html lang="en">
   <?php include 'includes/head.php' ?>

   <body>
       <?php include 'includes/menu.php' ?>

       <div class="container">
   ```

   Save and refresh. *Check: the page is styled and the menu shows Home, About PHP and About MariaDB.*

4. View the page source (Ctrl+U, or Cmd+Option+U on a Mac). *Check: the full `<head>` and `<nav>` are there, and there is no `include` anywhere.*

5. Add the same two lines to `aboutphp.php` and `aboutmariadb.php`. Use the menu to move between the three pages.

6. Your turn: add a footer to every page.
   - Create `includes/footer.php` containing:

     ```html
     <footer class="container border-top mt-4 pt-3 text-body-secondary">
         <p>Learning PHP, ICE Malta</p>
     </footer>
     ```

   - Include it in all three pages, just before `</body>`.

   *Check: the footer appears on every page. Change its text once in `footer.php`, and all three pages change.*

## Done when

- [ ] All three pages are styled and show the menu.
- [ ] All three pages show the footer, from one `includes/footer.php`.
- [ ] The page source shows no PHP code.
- [ ] You can say why including the menu is better than pasting it into each page.

## Previous / Next

Previous: [01 Your first PHP page](../01-first-php-page/README.md). Next: [03 Adding PHP code](../03-php-code/README.md).
