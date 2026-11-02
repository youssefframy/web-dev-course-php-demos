# Checkpoint 06: Loops

## Goal

Repeat code with `while`, `do...while`, `for` and `foreach`, and leave or skip part of a loop with `break` and `continue`.

## Concepts

- A loop runs the same block of code again and again. Each run is called an iteration.
- `while (condition) { ... }` checks the condition, and runs the block if it is true. Then it checks again.
- `do { ... } while (condition);` runs the block first, then checks.
- `for (start; condition; step) { ... }` puts the three parts of a counting loop on one line: set the counter, check it, and change it after each iteration.
- A loop that checks before each iteration is pre-tested. `while` and `for` are pre-tested, so they run 0 or more times.
- A loop that checks after each iteration is post-tested. `do...while` is post-tested, so it always runs at least once.
- `foreach ($array as $key => $value) { ... }` visits every item of an array in turn. You do not need a counter, and it stops by itself after the last item. Leave out `$key =>` if you only need the values.
- Inside a loop:
  - `break` ends the whole loop at once.
  - `continue` skips the rest of this iteration and goes on to the next one.
- `in_array($needle, $array)` is true if `$needle` is one of the values in `$array`.
- Make sure the condition becomes false at some point. If it never does, the loop never ends and the page never finishes loading. Stop the server with Ctrl+C and fix the loop.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/06-loops
php -S localhost:8000
```

## Steps

1. Follow along: your fishing permit runs out on day 31. In `loops.php`, under the comment, add:

   ```php
   echo '<h5>while</h5>';
   $fishingPermitExpires = 31;
   $currentDay = 25;
   while ($currentDay <= $fishingPermitExpires) {
       echo "<p>Fishing on day $currentDay</p>";
       $currentDay++;
   }

   echo '<h5>do...while</h5>';
   $fishingPermitExpires = 31;
   $currentDay = 25;
   do {
       echo "<p>Fishing on day $currentDay</p>";
       $currentDay++;
   } while ($currentDay <= $fishingPermitExpires);

   echo '<h5>for</h5>';
   $fishingPermitExpires = 31;
   for ($currentDay = 25; $currentDay <= $fishingPermitExpires; $currentDay++) {
       echo "<p>Fishing on day $currentDay</p>";
   }
   ```

   Visit <http://localhost:8000/loops.php>. *Check: the three lists are identical, days 25 to 31.*

2. Change all three starting days from `25` to `32`, past the end of the permit. Refresh. *Check: `while` and `for` print nothing. `do...while` prints `Fishing on day 32` once, because it only checks after the first run.* Change them back to `25`.

3. Follow along: loop through an array with `foreach`:

   ```php
   echo '<h5>foreach</h5>';
   $students = ['Alice', 'Bob', 'Craig', 'Daniela', 'Elisa'];
   foreach ($students as $id => $name) {
       echo "<p>Student $id: $name</p>";
   }
   ```

   *Check: five students, numbered from 0.* Add `'Frank'` to the end of the array. *Check: six students, and you did not touch the loop.*

4. Follow along: trick or treat. Each house adds 15 g of sweets, and the bucket holds 100 g. Stop as soon as one more house would overfill it:

   ```php
   echo '<h5>break and continue</h5>';
   $bucketCapacity = 100;
   $bucketWeight = 0;
   $numHouses = 20;
   for ($currentHouse = 1; $currentHouse <= $numHouses; $currentHouse++) {
       echo "<p>House $currentHouse. Trick or Treat!</p>";
       $bucketWeight += 15;
       if ($bucketWeight > $bucketCapacity - 15) {
           echo '<p>Bucket too full, returning home.</p>';
           break;
       }
   }
   ```

   *Check: houses 1 to 6, then home. The loop could have gone on to house 20, but `break` ended it.*

5. Follow along: some houses are too spooky to visit. Skip them with `continue`. Add the array above the loop:

   ```php
   $spookyHouses = [2, 5, 9, 18];
   ```

   and this `if` as the first thing inside the loop:

   ```php
   if (in_array($currentHouse, $spookyHouses)) {
       echo "<p>House $currentHouse is too spooky!</p>";
       continue;
   }
   ```

   *Check: houses 2 and 5 are too spooky, and you now get to house 8 before going home. A skipped house adds no sweets.*

6. Your turn: create `shopping.php` in this folder.
   - With a `for` loop, show the 7 times table from `1 x 7 = 7` to `10 x 7 = 70`.
   - Start with:

     ```php
     $budget = 10.00;
     $spent = 0;
     $basket = [
         'Bread' => 1.20,
         'Milk' => 0.95,
         'Ġbejniet' => 3.50,
         'Coffee' => 6.00,
         'Chocolate' => 2.40,
         'Apples' => 1.80,
     ];
     $outOfStock = ['Milk'];
     ```

     With one `foreach`, go through the basket. Skip anything out of stock with `continue`. If an item would take you over budget, say so and stop with `break`. Otherwise add it to `$spent` with `+=` and show `Bought Bread for 1.20`. After the loop, show `Spent 4.70 of 10.00`.
   - With a `while` loop, count down from 5 to 1, then show `Lift off!`.

   Visit <http://localhost:8000/shopping.php>. *Check: remove `'Milk'` from `$outOfStock` and the total becomes `5.65`. Raise the budget to `20` and everything except the milk is bought.*

## Done when

- [ ] `loops.php` shows the three fishing lists, the students, and the trick-or-treat round with both spooky houses skipped.
- [ ] `shopping.php` shows the times table, the shopping list with the skip and the stop, and the countdown.
- [ ] You can say which loop runs at least once, and why.
- [ ] You can say the difference between `break` and `continue`.

## Previous / Next

Previous: [05 Logical operators](../05-logical/README.md). Next: [07 Intro Website: menu, summary and dark mode](../07-intro-website/README.md).
