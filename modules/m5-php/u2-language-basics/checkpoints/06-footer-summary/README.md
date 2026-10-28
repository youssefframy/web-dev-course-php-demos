# Checkpoint 06: Footer and PHP Summary

## Goal

Give the Intro Website a footer with icons, and add a PHP Summary page that reports on the PHP running the site.

## Concepts

Nothing new about PHP itself. You use `printf()` and `var_dump()` from this unit on a real page, plus three functions that describe the PHP installation:

| Function | Returns |
|---|---|
| `phpversion()` | The PHP version, as a string such as `'8.3.23'`. |
| `get_loaded_extensions()` | An array with the name of every extension PHP has loaded. An extension adds features to PHP. For example, `pdo_mysql` lets PHP talk to MariaDB. |
| `extension_loaded('pdo_mysql')` | `true` if that one extension is loaded. |

Bootstrap Icons is a free set of icons from the makers of Bootstrap. After you load its stylesheet, `<i class="bi bi-github"></i>` shows the GitHub logo. Every icon has a name, listed at <https://icons.getbootstrap.com>.

## Before you start

Unit 2 has its own copy of the Intro Website in [`intro-website/`](../../intro-website), as it was at the end of Unit 1. To carry on with your own copy instead, copy it over the provided one:

```sh
cp -R modules/m5-php/u1-welcome-to-php/intro-website/. modules/m5-php/u2-language-basics/intro-website/
```

You need the four pages and the Configuration link from Unit 1's self-study. Start the server:

```sh
cd modules/m5-php/u2-language-basics/intro-website
php -S localhost:8000
```

## Steps

1. Follow along: load Bootstrap Icons. In `includes/head.php`, add this line under the Bootstrap stylesheet:

   ```html
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   ```

2. Follow along: replace everything in `includes/footer.php` with:

   ```php
   <div class="container">
       <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
           <div class="col-md-6 d-flex align-items-center">
               <a href="/" class="me-2 text-body-secondary text-decoration-none lh-1" aria-label="Home">
                   <i class="bi bi-rocket-takeoff fs-4"></i>
               </a>
               <span class="text-body-secondary">&copy; <?= date('Y') ?> Learning PHP, ICE Malta</span>
           </div>

           <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
               <li class="ms-3">
                   <a class="text-body-secondary" href="https://www.php.net" aria-label="PHP">
                       <i class="bi bi-filetype-php fs-4"></i>
                   </a>
               </li>
               <li class="ms-3">
                   <a class="text-body-secondary" href="https://mariadb.org" aria-label="MariaDB">
                       <i class="bi bi-database fs-4"></i>
                   </a>
               </li>
               <li class="ms-3">
                   <a class="text-body-secondary" href="https://github.com" aria-label="GitHub">
                       <i class="bi bi-github fs-4"></i>
                   </a>
               </li>
           </ul>
       </footer>
   </div>
   ```

   `date('Y')` keeps the copyright year up to date by itself. `aria-label` gives each icon link a name that screen readers can say aloud.

   Visit <http://localhost:8000> and click through the pages. *Check: every page has the new footer, with a rocket on the left and three icons on the right. You only changed one file.*

3. Follow along: add a fifth link to `includes/menu.php`, after Configuration:

   ```php
   <li class="nav-item">
       <a class="nav-link <?= $_SERVER['SCRIPT_NAME'] === '/phpsummary.php' ? 'active' : '' ?>" href="phpsummary.php">PHP Summary</a>
   </li>
   ```

4. Follow along: create `phpsummary.php`:

   ```php
   <!doctype html>
   <html lang="en">
   <?php include 'includes/head.php' ?>

   <body>
       <?php include 'includes/menu.php' ?>

       <div class="container">
           <h1>PHP Summary</h1>
           <?php
           printf('<p>You are running PHP %s.</p>', phpversion());

           echo '<h2>Loaded extensions</h2>';
           $extensions = get_loaded_extensions();
           echo '<pre>';
           var_dump($extensions);
           echo '</pre>';

           echo '<h2>Server environment</h2>';
           echo '<pre>';
           var_dump($_SERVER);
           echo '</pre>';
           ?>
       </div>

       <?php include 'includes/footer.php' ?>
   </body>

   </html>
   ```

   Click PHP Summary in the menu. *Check: the version starts with `8.3`, the link is highlighted, and you can find `SCRIPT_NAME` in the server environment. Its value is `/phpsummary.php`.*

5. Your turn: on the PHP Summary page, under the version and before the list of extensions, add two lines:
   - The number of extensions, with `printf()` and `%d`. `count($extensions)` gives the number of items in an array.
   - Whether the `pdo_mysql` extension is loaded, shown as `yes` or `no`. You will need it to connect to MariaDB in Unit 8.

   *Check: something like `38 extensions are loaded.` and `pdo_mysql loaded: yes`. If it says `no`, rebuild the dev container (command palette, Dev Containers: Rebuild Container).*

## Done when

- [ ] Every page shows the new footer with the current year and four icons.
- [ ] The menu has a PHP Summary link, which is highlighted on that page only.
- [ ] The PHP Summary page shows the version, the number of extensions, whether `pdo_mysql` is loaded, the list of extensions and the server environment.
- [ ] You can say why the extensions list needs `var_dump()` and not `echo`.

## Previous / Next

Previous: [05 Inspecting variables](../05-inspecting-variables/README.md). Next: [07 Self-study](../07-self-study/README.md).
