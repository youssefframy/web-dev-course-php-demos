# Checkpoint 05: Sorting

## Goal

Sort arrays by value or by key, in either direction, with your own comparison function, and in the order a person would expect.

## Concepts

- Sorting functions change the array you give them. They return `true`, not a new array.
- The name tells you what the function does:

  | Sorts by | Keeps keys | Ascending | Descending | Your own function |
  |---|---|---|---|---|
  | Value | No, keys reset | `sort()` | `rsort()` | `usort()` |
  | Value | Yes | `asort()` | `arsort()` | `uasort()` |
  | Key | Yes | `ksort()` | `krsort()` | `uksort()` |

- Use a function that keeps keys for an associative array, or the string keys are lost.
- A comparison function takes two items, `$a` and `$b`, and returns a number below 0 if `$a` goes first, 0 if they are equal, and above 0 if `$b` goes first.
- The spaceship operator `<=>` returns exactly that: -1, 0 or 1. So `$a <=> $b` sorts ascending, and `$b <=> $a` sorts descending.
- `array_multisort()` sorts several arrays together, so that items in the same position stay together. It sorts by the first array, then uses the next to settle ties.
- `sort()` compares strings one character at a time, so `'Module 12'` comes before `'Module 5'`. `natsort()` uses natural order, like a person. `natcasesort()` ignores case.

## Before you start

Create `05-sorting.php` in the unit's [`arrays/`](../../arrays) folder, starting with:

```php
<?php
echo '<pre>';

function fresh(): array
{
    return [
        'indexed' => ['Gregory', 'Alice', 'Travis', 'Paula', 'Olivia'],
        'assoc' => ['Goaler' => 'Travis', 'Attacker' => 'Paula', 'Defender' => 'Olivia', 'Midfielder' => 'Gregory', 'Wingman' => 'Alice']
    ];
}
```

`fresh()` hands back the unsorted arrays each time, so every step starts from the same data.

## Steps

1. Follow along: `sort()`, which resets keys.

   ```php
   $n = fresh();
   sort($n['indexed']);
   sort($n['assoc']);
   print_r($n);
   ```

   *Check: both are sorted by name. The associative array has lost its position names.*

2. Follow along: `asort()`, which keeps keys.

   ```php
   $n = fresh();
   asort($n['indexed']);
   asort($n['assoc']);
   print_r($n);
   ```

   *Check: the associative array is in alphabetical order, with `Wingman => Alice` first. The indexed array keeps each name's old key, so the keys are out of order.*

3. Follow along: sort by key, and sort in descending order.

   ```php
   $n = fresh();
   ksort($n['assoc']);
   print_r($n['assoc']);

   $n = fresh();
   arsort($n['assoc']);
   print_r($n['assoc']);
   ```

   *Check: first by position name, from `Attacker`, then by player name from `Travis` down to `Alice`.*

4. Follow along: your own comparison. Put the day with the highest reading first.

   ```php
   $readings = [
       'Monday' => [12, 14, 16, 9],
       'Tuesday' => [13, 16, 18, 3],
       'Wednesday' => [9, 10, 8, 1]
   ];
   uasort($readings, fn($a, $b) => max($b) <=> max($a));
   print_r($readings);
   ```

   *Check: Tuesday (18), Monday (16), Wednesday (10).* The function compares the highest reading of each day. `$b` comes first on the left, to sort in descending order.

5. Follow along: sort three related arrays together.

   ```php
   $studentNames = ['Peter', 'Alice', 'Davinia', 'Bob', 'Travis'];
   $studentAges = [26, 18, 21, 37, 45];
   $studentGrades = [82, 90, 92, 76, 71];
   array_multisort($studentNames, SORT_ASC, $studentAges, SORT_DESC, $studentGrades, SORT_DESC);
   print_r([$studentNames, $studentAges, $studentGrades]);
   ```

   *Check: Alice is first in all three arrays, with age 18 and grade 90.* The three arrays moved together.

6. Follow along: natural order.

   ```php
   $folders = ['Module 5', 'Module 1', 'Module 12'];
   sort($folders);
   print_r($folders);
   natsort($folders);
   print_r($folders);
   ```

   *Check: `sort()` gives Module 1, Module 12, Module 5. `natsort()` gives Module 1, Module 5, Module 12, with the keys 0, 2, 1 kept.*

7. Your turn: make an array of four products, each with a `name` and a `price`, as an array of arrays. Use `usort()` to list them from the cheapest to the dearest, then `uasort()` and a second comparison on `name` to put them in alphabetical order. *Check: with `usort()` the keys are 0 to 3 in the new order. With `uasort()` they are in the old order.*

## Done when

- [ ] You can say which sort function to use to keep the key and value pairs together.
- [ ] You can write a comparison function with `<=>`, for ascending and descending.
- [ ] You can say why `sort()` puts `Module 12` before `Module 5`.

## Previous / Next

Previous: [04 Traversing and searching](../04-traversing-searching/README.md). Next: [06 Array operations](../06-array-operations/README.md).
