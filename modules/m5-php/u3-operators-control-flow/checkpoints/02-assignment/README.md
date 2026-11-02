# Checkpoint 02: Assignment operators

## Goal

Update a variable using its own value, and see the difference between copying a value and pointing a second name at the same variable.

## Concepts

- `=` stores a value in a variable.
- An assignment with an operation works out a new value from the variable's current value, then stores it back in the same variable:

  | Short form | Means |
  |---|---|
  | `$a += 6` | `$a = $a + 6` |
  | `$a -= 4` | `$a = $a - 4` |
  | `$a *= 2` | `$a = $a * 2` |
  | `$a /= 2` | `$a = $a / 2` |
  | `$a %= 2` | `$a = $a % 2` |
  | `$a .= ' text'` | `$a = $a . ' text'` |

- A variable can change type along the way. After `.=`, a variable that held an int holds a string.
- Assignment by value is the default. `$b = $a` puts a copy of `$a`'s value in `$b`. They are now two separate variables, and changing one leaves the other alone.
- Assignment by reference uses `&`. `$b = &$a` makes `$b` another name for `$a`. There is still only one value, so changing `$b` changes `$a`.
- Objects are the exception: a variable holding an object only holds a handle to it, so copying the variable still points at the same object. You will meet this when you build classes.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/02-assignment
php -S localhost:8000
```

## Steps

1. Follow along: in `assignment.php`, under the comment, add:

   ```php
   echo '<h5>Assignment With Operation</h5>';
   $a = 1;
   printf('<p>a starts with the value %d</p>', $a);
   printf('<p>a+=6: %d</p>', $a += 6);
   printf('<p>a-=4: %d</p>', $a -= 4);
   printf('<p>a*=2: %d</p>', $a *= 2);
   printf('<p>a/=2: %d</p>', $a /= 2);
   printf('<p>a%%=2: %d</p>', $a %= 2);
   echo '<p>' . $a .= ' is the final result</p>';
   ```

   Visit <http://localhost:8000/assignment.php>. *Check: `7`, `3`, `6`, `3`, `1`, then `1 is the final result`. Each line starts from the result of the line before.*

   If you copy this from the slides, retype the quote at the start of the `+=` line. The slide has a curly `‘`, which PHP does not accept.

2. Add `var_dump($a);` at the end and refresh. *Check: `string(...)`. `$a` started as an int and is now a string.* Remove the line.

3. Follow along: copy a value:

   ```php
   echo '<h5>Assignment By Value</h5>';
   $a = 20;
   printf('<p>a starts with the value %d</p>', $a);
   $b = $a;
   $b = 30;
   printf('<p>After changing b, a contains %d</p>', $a);
   ```

   *Check: `a contains 20`. `$b` got its own copy.*

4. Follow along: now share it:

   ```php
   echo '<h5>Assignment By Reference</h5>';
   $a = 20;
   printf('<p>a starts with the value %d</p>', $a);
   $b = &$a;
   $b = 30;
   printf('<p>After changing b, a contains %d</p>', $a);
   ```

   *Check: `a contains 30`. The only difference from step 3 is the `&`.*

5. Your turn: create `wallet.php` in this folder. Start with `$balance = 50;` and show it with `%.2f`. Then, using only assignment operators and showing the balance after each one:
   - spend 12.50 on a book
   - receive 20 as a birthday gift
   - add 5% interest (multiply by `1.05`), with a label that shows the `%` sign
   - copy the balance into `$copy`, set `$copy` to `0`, and show that the balance has not changed
   - make `$sharedBalance` a reference to `$balance`, take 10 off `$sharedBalance`, and show that the balance has changed

   Visit <http://localhost:8000/wallet.php>. *Check: the last two lines are `60.38` and `50.38`.*

## Done when

- [ ] `assignment.php` shows the three sections with the results from steps 1, 3 and 4.
- [ ] `wallet.php` shows six balances, ending with `60.38` and `50.38`.
- [ ] You can say what `$a .= 'x'` does to an int.
- [ ] You can say, in one sentence, the difference between `$b = $a` and `$b = &$a`.

## Previous / Next

Previous: [01 Arithmetic operators](../01-arithmetic/README.md). Next: [03 Comparison and conditional operators](../03-comparison/README.md).
