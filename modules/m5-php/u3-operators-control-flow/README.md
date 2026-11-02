# Unit 3: Operators and Control Flow

In this unit you learn PHP's operators, then use them to make decisions with `if` and `switch` and to repeat code with loops. Then you go back to the Intro Website, build its menu from an array, tidy up the PHP Summary page and add a dark mode switch.

## What you need

The same dev container as Units 1 and 2. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`.

## How to run your pages

Checkpoints 01 to 06 each have their own folder of practice files. Start the server in the checkpoint's folder, for example:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/01-arithmetic
php -S localhost:8000
```

Checkpoints 07 and 08 work on the Intro Website, in [`intro-website/`](intro-website). Start the server there instead.

When you move to the next checkpoint, stop the server with Ctrl+C, `cd` to the new folder and start it again. Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Arithmetic operators](checkpoints/01-arithmetic/README.md) | `+ - * / % **`, escaping `%` in `printf()`, `++` and `--` |
| 02 | [Assignment operators](checkpoints/02-assignment/README.md) | `+=` and friends, assignment by value and by reference |
| 03 | [Comparison and conditional operators](checkpoints/03-comparison/README.md) | `==` and `===`, the spaceship, logical and array operators, `?:` and `??` |
| 04 | [Selection](checkpoints/04-selection/README.md) | `if`, `elseif`, `else`, the alternative syntax, the ternary and `switch` |
| 05 | [Logical operators](checkpoints/05-logical/README.md) | Combining conditions with `&&`, `\|\|`, `xor` and `!` |
| 06 | [Loops](checkpoints/06-loops/README.md) | `while`, `do...while`, `for`, `foreach`, `break` and `continue` |
| 07 | [Intro Website: menu, summary and dark mode](checkpoints/07-intro-website/README.md) | A menu built from an array, badges and a table, a dark mode cookie |
| 08 | [Self-study](checkpoints/08-self-study/README.md) | Footer links from an array, and the extra credit |

Checkpoints 01 to 06 do not depend on each other, but they are easiest in order. Checkpoint 08 builds on 07.
