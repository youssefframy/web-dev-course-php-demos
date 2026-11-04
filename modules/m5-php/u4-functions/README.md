# Unit 4: Functions

In this unit you split your code into functions: named blocks you write once and call whenever you need them. You start by finishing last unit's extra credit together, so that the Intro Website follows the visitor's system theme. Then you write your own functions, learn how PHP passes values in and out of them, and use anonymous functions, type hints and recursion. Finally you use functions on the Intro Website to list its folders, show DNS records and display posts from Mastodon.

## What you need

The same dev container as Units 1 to 3. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`. Checkpoints 06 and 07 also need an internet connection, because the site fetches data from Mastodon.

## How to run your pages

Checkpoints 02 to 05 each have their own folder of practice files. Start the server in the checkpoint's folder, for example:

```sh
cd modules/m5-php/u4-functions/checkpoints/02-functions
php -S localhost:8000
```

Checkpoints 01, 06 and 07 work on the Intro Website, in [`intro-website/`](intro-website). Start the server there instead.

When you move to the next checkpoint, stop the server with Ctrl+C, `cd` to the new folder and start it again. Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Dark mode from the system setting](checkpoints/01-system-darkmode/README.md) | The Unit 3 extra credit: `matchMedia()` sets the first `darkmode` cookie |
| 02 | [Writing functions](checkpoints/02-functions/README.md) | `function` and `return`, by value and by reference, default and variadic parameters |
| 03 | [Return values and scope](checkpoints/03-scope/README.md) | Returning by reference, global and local variables, nested functions |
| 04 | [Functions as values](checkpoints/04-functional/README.md) | Variable functions, anonymous and arrow functions, callbacks, closures and `use` |
| 05 | [Types, static variables and recursion](checkpoints/05-types-static-recursion/README.md) | Type hints, `static`, and a function that calls itself |
| 06 | [Intro Website: folders, DNS and Mastodon](checkpoints/06-intro-website/README.md) | A recursive directory listing and a Mastodon page built from functions |
| 07 | [Self-study](checkpoints/07-self-study/README.md) | Links to the original posts, and the extra credit |

Checkpoints 02 to 05 do not depend on each other, but they are easiest in order. Checkpoint 06 builds on 01, and 07 builds on 06.
