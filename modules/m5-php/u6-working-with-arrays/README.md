# Unit 6: Working With Arrays

Arrays are the most used data structure in PHP. In this unit you look at what an array really is in PHP, how to create, read, search and traverse one, how to sort and combine arrays, and how to make an array behave like a set, a queue or a stack. Then you put it to work in StudentDB, a small app that stores students in the session and in cookies, and sorts them.

## What you need

The same dev container as Units 1 to 5. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`.

## How to run your pages

Checkpoints 01 to 07 are short experiments. Put each one in its own file in the unit's [`arrays/`](arrays) folder, and start the server there:

```sh
cd modules/m5-php/u6-working-with-arrays/arrays
php -S localhost:8000
```

Checkpoints 08 and 09 work on the app in [`studentdb/`](studentdb). Start the server in that folder instead.

Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Creating arrays](checkpoints/01-creating-arrays/README.md) | Indexed and associative arrays, reading values, `range()`, `count()` and `array_pad()` |
| 02 | [Multi-dimensional arrays](checkpoints/02-multi-dimensional/README.md) | Arrays inside arrays, and reading a value with several keys |
| 03 | [Array functions](checkpoints/03-array-functions/README.md) | Taking arrays apart and putting them together |
| 04 | [Traversing and searching](checkpoints/04-traversing-searching/README.md) | `foreach` by value and by reference, `array_walk()`, `in_array()` and `array_search()` |
| 05 | [Sorting](checkpoints/05-sorting/README.md) | The sort functions, your own comparison function, and natural order |
| 06 | [Array operations](checkpoints/06-array-operations/README.md) | `array_sum()`, `array_merge()`, `array_diff()` and `array_filter()` |
| 07 | [Sets, queues and stacks](checkpoints/07-sets-queues-stacks/README.md) | Using an array as an abstract data type |
| 08 | [StudentDB](checkpoints/08-studentdb/README.md) | The unit project: a student list kept in the session and cookies, with sorting |
| 09 | [Self-study](checkpoints/09-self-study/README.md) | Sort direction, a direction icon, and the extra credit: search |

Checkpoints 01 to 07 are independent of each other. Checkpoint 09 builds on 08.
