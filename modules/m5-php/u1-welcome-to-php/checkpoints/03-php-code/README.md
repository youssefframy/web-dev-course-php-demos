# Checkpoint 03: Adding PHP code

## Goal

Let PHP work out values for you: how old PHP is today, and when each page was last changed.

## Concepts

- `new DateTime("1995-06-08")` creates an object that holds a date. `new DateTime()`, with nothing in the brackets, holds the date and time right now.
- `$a->diff($b)` gives the difference between two dates. Its `y` value is the number of whole years, so `$phpAge->y` is PHP's age in years.
- A UNIX timestamp is a date stored as the number of seconds since midnight on 1 January 1970.
- `filemtime("aboutphp.php")` returns the time a file was last saved, as a UNIX timestamp.
- `date("Y-m-d H:i:s", $timestamp)` turns a timestamp into readable text. Each letter stands for one part of the date:

  | Letter | Means | Example |
  |---|---|---|
  | `Y` | four-digit year | 2026 |
  | `m` | two-digit month | 03 |
  | `d` | two-digit day | 09 |
  | `H` | hour, 24-hour clock | 17 |
  | `i` | minutes | 05 |
  | `s` | seconds | 42 |

  Any other character, such as `-`, `:` or `/`, is copied as it is.

Because the age is calculated every time someone visits, the page is never out of date.

## Before you start

You need the Intro Website from checkpoint 02, with the head, menu and footer included on every page. If you skipped it, copy the reference files over yours:

```sh
cp -R solutions/modules/m5-php/u1-welcome-to-php/checkpoints/02-includes/intro-website/. modules/m5-php/u1-welcome-to-php/intro-website/
```

Start the server in `intro-website/` if it is not running.

## Steps

1. Follow along: at the very top of `index.php`, before `<!doctype html>`, add:

   ```php
   <?php
       $phpReleaseDate = new DateTime("1995-06-08");
       $phpAge = $phpReleaseDate->diff(new DateTime());
   ?>
   ```

   8 June 1995 is the day the first version of PHP was released.

2. In the second paragraph of `index.php`, show the age:

   ```php
   <p>Select a link above to start learning. about a language with <?= $phpAge->y ?> years of heritage!</p>
   ```

   Save and refresh. *Check: the sentence now has a number of years in it. Work out the number yourself and compare.*

3. Follow along: in `aboutphp.php`, add a new paragraph after the existing one, inside the container:

   ```php
   <p>
       <small><em>Last updated: <?= date("Y-m-d H:i:s.", filemtime("aboutphp.php")) ?></em></small>
   </p>
   ```

   *Check: the date and time you last saved the file.*

4. Save `aboutphp.php` again without changing anything, then refresh. The time moves on.

5. Add the same paragraph to `aboutmariadb.php`. Change the file name inside `filemtime()` to `"aboutmariadb.php"`, or it shows the wrong file's time.

6. Your turn: add a "Last updated" paragraph to `index.php` too, but show the date the way it is usually written in Malta, day first, without seconds. *Check: something like `Last updated: 26/10/2026 17:30`.*

## Done when

- [ ] The home page shows PHP's age, worked out by PHP.
- [ ] About PHP and About MariaDB each show their own "Last updated" time.
- [ ] The home page shows its "Last updated" time as day/month/year.
- [ ] You can say what `filemtime()` returns, and why it has to go through `date()` before you show it.

## Previous / Next

Previous: [02 Including sections](../02-includes/README.md). Next: [04 Highlighting the menu](../04-active-menu/README.md).
