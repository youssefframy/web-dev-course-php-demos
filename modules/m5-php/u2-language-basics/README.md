# Unit 2: Language Basics

In this unit you learn the building blocks of PHP: strings, data types, constants, a first look at functions, formatted output and inspecting variables. Then you go back to the Intro Website and add a proper footer and a PHP Summary page.

## What you need

The same dev container as Unit 1. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`.

## How to run your pages

Checkpoints 01 to 05 each have their own folder of practice files. Start the server in the checkpoint's folder, for example:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/01-strings
php -S localhost:8000
```

Checkpoints 06 and 07 work on the Intro Website, in [`intro-website/`](intro-website). Start the server there instead.

The server only serves the folder you started it in. When you move to the next checkpoint, stop it with Ctrl+C, `cd` to the new folder and start it again.

## Keeping your code tidy

From this unit on, format every PHP file before you save it. Right-click in the file and choose Format Document, or press Shift+Alt+F (Shift+Option+F on a Mac). The DEVSENSE extension lays your code out to PSR-12, the coding style most PHP teams follow. The main points:

- Indent with 4 spaces.
- Keep lines under 120 characters, and ideally under 80.
- Write keywords and types in lower case: `true`, `null`, `echo`.
- Put a space after each comma in a list of arguments, and none before it.
- Leave out the closing `?>` in a file that contains only PHP.

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Strings and comments](checkpoints/01-strings/README.md) | Concatenation, single and double quotes, heredoc and nowdoc, comments |
| 02 | [Data types](checkpoints/02-data-types/README.md) | `gettype()`, truthy and falsy values, type coercion and casting |
| 03 | [Constants and functions](checkpoints/03-constants-functions/README.md) | `define()`, `const`, your first function, variable functions |
| 04 | [Formatting output](checkpoints/04-formatting-output/README.md) | `print`, `printf()` format specifiers, string functions |
| 05 | [Inspecting variables](checkpoints/05-inspecting-variables/README.md) | `print_r()`, `var_dump()`, `var_export()`, `isset()`, `empty()` |
| 06 | [Footer and PHP Summary](checkpoints/06-footer-summary/README.md) | The Intro Website gets an icon footer and a PHP Summary page |
| 07 | [Self-study](checkpoints/07-self-study/README.md) | Request details on the PHP Summary page, and the extra credit |

Checkpoints 01 to 05 do not depend on each other, but they are easiest in order. Checkpoint 07 builds on 06.

Previous: [Unit 1](../u1-welcome-to-php/README.md). Back to [Module 5](../README.md).
