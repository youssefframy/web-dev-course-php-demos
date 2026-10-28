# Checkpoint 04: Highlighting the menu

## Goal

Make the menu highlight the page you are on, even though every page shares the same `menu.php`.

## Concepts

Bootstrap highlights a menu link when the link has the `active` class. You cannot type `active` into `menu.php` by hand: there is only one menu file, so the same link would be highlighted on every page. PHP has to decide, each time, which link gets it.

- `$_SERVER` is a superglobal: an array that PHP fills in for you on every request. It describes the server and the current request.
- `$_SERVER['SCRIPT_NAME']` is the path of the page being run, for example `/aboutphp.php`. Visiting `/` runs `/index.php`.
- A ternary picks one of two values:

  ```
  condition ? value if true : value if false
  ```

  `$_SERVER['SCRIPT_NAME'] === '/aboutphp.php' ? 'active' : ''` gives `active` on the About PHP page, and an empty string everywhere else. `===` checks whether two values are exactly the same.

You will meet arrays, comparisons and conditions properly in later units. For now, read the line as "if this is the About PHP page, write `active`".

## Before you start

You need the Intro Website from checkpoint 03. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u1-welcome-to-php/checkpoints/03-php-code/intro-website/. modules/m5-php/u1-welcome-to-php/intro-website/
```

Start the server in `intro-website/` if it is not running.

## Steps

1. Click through the three pages. The menu looks the same on all of them.

2. Your turn, before the change: add this line just before the footer include on each of the three pages:

   ```php
   <p><code><?= $_SERVER['SCRIPT_NAME'] ?></code></p>
   ```

   Visit each page, and note what it shows. *Check: the home page shows `/index.php`, even when the address bar only says `localhost:8000/`.* Then remove the three lines.

3. Follow along: in `includes/menu.php`, change the class of each of the three `nav-link` links:

   ```php
   <li class="nav-item">
       <a class="nav-link <?= $_SERVER['SCRIPT_NAME'] === '/index.php' ? 'active' : '' ?>" aria-current="page" href="/">Home</a>
   </li>
   <li class="nav-item">
       <a class="nav-link <?= $_SERVER['SCRIPT_NAME'] === '/aboutphp.php' ? 'active' : '' ?>" href="aboutphp.php">About PHP</a>
   </li>
   <li class="nav-item">
       <a class="nav-link <?= $_SERVER['SCRIPT_NAME'] === '/aboutmariadb.php' ? 'active' : '' ?>" href="aboutmariadb.php">About MariaDB</a>
   </li>
   ```

   Save, then click through the pages. *Check: the link for the page you are on is darker than the others.*

4. View the page source on About MariaDB, and find the menu. *Check: only that link has `class="nav-link active"`. The others have `class="nav-link "`.*

## Done when

- [ ] Each page highlights its own menu link, and only that one.
- [ ] The temporary `SCRIPT_NAME` lines are removed.
- [ ] You can read the ternary in `menu.php` out loud as a sentence.

## Previous / Next

Previous: [03 Adding PHP code](../03-php-code/README.md). Next: [05 Self-study](../05-self-study/README.md).
