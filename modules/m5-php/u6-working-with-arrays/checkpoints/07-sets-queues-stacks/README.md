# Checkpoint 07: Sets, queues and stacks

## Goal

Use an array to behave like a set, a queue and a stack.

## Concepts

- An abstract data type (ADT) is described by what it holds and what you can do with it, not by how it is stored. A set, a queue and a stack are three ADTs.
- PHP has no built-in type for them. You use an array, and only the functions that match the ADT's operations.
- A set holds unique items, in no particular order. Its operations are union (everything in either set), intersection (what is in both) and difference (what is in one and not the other).
  - Union: `array_unique(array_merge($a, $b))`.
  - Intersection: `array_intersect($a, $b)`.
  - Difference: `array_diff($a, $b)`.
- A queue is first in, first out, like a queue at a counter. You enqueue at the end with `array_push()`, and dequeue from the front with `array_shift()`.
- A stack is last in, first out, like a pile of plates. You push at the front with `array_unshift()` and pop from the front with `array_shift()`. Pushing with `array_push()` and popping with `array_pop()` at the end of the array works just as well. This unit uses the front, so that the newest item is item 0.
- `array_push()`, `array_shift()` and `array_unshift()` change the array. `array_shift()` and `array_unshift()` renumber the keys.

## Before you start

Create `07-adts.php` in the unit's [`arrays/`](../../arrays) folder, starting with:

```php
<?php
echo '<pre>';
```

## Steps

1. Follow along: set operations.

   ```php
   $maltaCities = ['Valletta', 'Safi', 'Rabat', 'Zebbug', 'Qormi'];
   $moroccoCities = ['Casablanca', 'Rabat', 'Marrakech', 'Safi'];

   echo "Set union\n";
   print_r(array_unique(array_merge($maltaCities, $moroccoCities)));
   echo "Set intersection\n";
   print_r(array_intersect($maltaCities, $moroccoCities));
   echo "Set difference\n";
   print_r(array_diff($maltaCities, $moroccoCities));
   ```

   *Check: the union has seven cities, with no repeats and a gap in the keys. The intersection is Safi and Rabat. The difference is Valletta, Zebbug and Qormi.* Take `array_unique()` away from the union, and the repeats are back.

2. Follow along: a queue.

   ```php
   $names = ['Alice', 'Bob', 'Charles', 'Daniela'];
   array_push($names, 'Eliott');
   print_r($names);
   $name = array_shift($names);
   echo "Dequeued: $name\n";
   print_r($names);
   ```

   *Check: Eliott joins at the end, and Alice is the one to leave.*

3. Follow along: a stack, from the same starting names.

   ```php
   $names = ['Alice', 'Bob', 'Charles', 'Daniela'];
   array_unshift($names, 'Eliott');
   print_r($names);
   $name = array_shift($names);
   echo "Popped: $name\n";
   print_r($names);
   ```

   *Check: Eliott joins at the front, and Eliott is the one to leave.*

4. Your turn: a browser's back button is a stack. Start with an empty array `$history`. Push three page names, pop one, and print the one now at the top with `$history[0]`. Then write a short queue for a printer: three jobs go in, and you take them out one by one in a `while (count($queue) > 0)` loop.

## Done when

- [ ] You can name the function for each of the set operations.
- [ ] You can say which end of an array a queue adds to and removes from.
- [ ] You can say how a queue and a stack differ in which item they hand back.

## Previous / Next

Previous: [06 Array operations](../06-array-operations/README.md). Next: [08 StudentDB](../08-studentdb/README.md).
