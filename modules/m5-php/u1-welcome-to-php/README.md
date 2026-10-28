# Unit 1: Welcome to PHP

In this unit you write your first PHP, run it with PHP's built-in web server, and start the Intro Website. This is a small site about PHP and MariaDB that you keep building in the next units.

## What you need

PHP 8.3 is already installed in the dev container. Open a terminal in VS Code (Terminal > New Terminal) and check:

```sh
php -v
```

The first line should start with `PHP 8.3`.

The dev container also installs the DEVSENSE PHP extension, which colours and checks your PHP code. If you opened the container before this unit was added, rebuild it once: open the command palette (Ctrl+Shift+P, or Cmd+Shift+P on a Mac) and choose Dev Containers: Rebuild Container.

## How to run your pages

PHP comes with a small web server for development. Start it in the folder that holds your pages:

```sh
cd modules/m5-php/u1-welcome-to-php/intro-website
php -S localhost:8000
```

Then open <http://localhost:8000> in your browser. VS Code forwards port 8000 from the container to your computer.

- The server keeps running in that terminal. Press Ctrl+C to stop it.
- You do not need to restart it after you change a file. Save, then refresh the browser.
- It serves only the folder you started it in. To work on a different folder, stop it, `cd` to the other folder and start it again.
- Always open your pages through `http://localhost:8000`. If you open a `.php` file straight from disk, the browser shows your code instead of running it.

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Your first PHP page](checkpoints/01-first-php-page/README.md) | `<?php ?>`, `echo`, `<?= ?>` and `phpinfo()` |
| 02 | [Including sections](checkpoints/02-includes/README.md) | The Intro Website shares one head and one menu |
| 03 | [Adding PHP code](checkpoints/03-php-code/README.md) | PHP's age and "Last updated" dates |
| 04 | [Highlighting the menu](checkpoints/04-active-menu/README.md) | The menu shows which page you are on |
| 05 | [Self-study](checkpoints/05-self-study/README.md) | A Configuration page, and the extra credit |

Checkpoints 02 to 05 all work on the same site, in [`intro-website/`](intro-website). Do them in order.

Back to [Module 5](../README.md).
