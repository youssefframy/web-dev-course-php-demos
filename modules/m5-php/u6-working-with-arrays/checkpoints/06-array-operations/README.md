# Checkpoint 06: Array operations

## Goal

Total, merge, subtract and filter arrays with the functions PHP provides for the whole array at once.

## Concepts

- `array_sum()` adds up all the values.
- `array_merge()` joins two or more arrays into a new one. Numeric keys are renumbered, so nothing is overwritten. For string keys, a later array wins.
- `array_diff($a, $b)` returns the values of `$a` that are not in `$b`. The keys of `$a` are kept.
- `array_filter($array, $callback)` keeps the items for which the callback returns true. The keys are kept, so the result can have gaps. `array_values()` renumbers them.
- `str_contains($text, $part)` is true when `$part` is somewhere in `$text`. It is clearer than `strpos()` for a yes or no question, because `strpos()` returns the position, and position 0 is falsy.

## Before you start

Create `06-operations.php` in the unit's [`arrays/`](../../arrays) folder, starting with:

```php
<?php
echo '<pre>';
```

## Steps

1. Follow along: sum.

   ```php
   $fileSizes = [124, 67, 1045, 23];
   printf("Total file size: %dKb\n", array_sum($fileSizes));
   ```

   *Check: `Total file size: 1259Kb`.*

2. Follow along: merge and diff.

   ```php
   $maltaCities = ['Valletta', 'Safi', 'Rabat', 'Zebbug', 'Qormi'];
   $moroccoCities = ['Casablanca', 'Rabat', 'Marrakech', 'Safi'];

   print_r(array_merge($maltaCities, $moroccoCities));
   print_r(array_diff($maltaCities, $moroccoCities));
   ```

   *Check: nine cities, with Rabat and Safi twice. Then Valletta, Zebbug and Qormi, with the keys 0, 3 and 4.* The diff takes the Moroccan cities away from the Maltese ones.

3. Follow along: filter.

   ```php
   $emails = ['keith@icemalta.com', 'bob@gmail.com', 'alice@icemalta.com', 'travis@outlook.com'];

   $internal = array_filter($emails, fn($email) => str_contains($email, '@icemalta.com'));
   print_r($internal);
   print_r(array_values($internal));
   ```

   *Check: two addresses with the keys 0 and 2, then the same two with the keys 0 and 1.* The callback is called for each address, and returns true for those to keep.

4. Your turn:
   - Make an array of exam marks, and use `array_filter()` to keep only the passes, those of 50 or more. Print how many passed, and the average of the passes with `array_sum()` and `count()`.
   - Use `array_diff()` to list the Moroccan cities that are not Maltese.

## Done when

- [ ] You can say which keys `array_merge()` and `array_diff()` keep.
- [ ] You can say what `array_filter()`'s callback must return.
- [ ] You can say when you need `array_values()` after `array_filter()`.

## Previous / Next

Previous: [05 Sorting](../05-sorting/README.md). Next: [07 Sets, queues and stacks](../07-sets-queues-stacks/README.md).
