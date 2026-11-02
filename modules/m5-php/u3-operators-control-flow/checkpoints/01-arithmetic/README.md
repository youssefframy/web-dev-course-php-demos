# Checkpoint 01: Arithmetic operators

## Goal

Do sums in PHP, show the results with `printf()`, and count up and down with `++` and `--`.

## Concepts

An operator does something with one or more values. The values it works on are its operands. In `21 * 2`, `*` is the operator and `21` and `2` are the operands.

| Operator | Name | Gives |
|---|---|---|
| `+$a` | Identity | `$a` as an int or a float, whichever fits |
| `-$a` | Negation | `$a` with its sign flipped |
| `$a + $b` | Addition | The sum |
| `$a - $b` | Subtraction | The difference |
| `$a * $b` | Multiplication | The product |
| `$a / $b` | Division | The quotient. A float, unless both are ints and divide exactly, then an int |
| `$a % $b` | Modulo | The remainder after dividing `$a` by `$b` |
| `$a ** $b` | Exponentiation | `$a` to the power of `$b` |

- In a `printf()` format string, `%` starts a specifier such as `%d`. To print a real percent sign, write it twice: `%%`.
- `++` adds 1 to a variable and `--` takes 1 away. Where you put them matters when the value is used in the same statement:
  - `++$a`, prefix: add 1 first, then use the new value.
  - `$a++`, postfix: use the current value first, then add 1.
- PHP works out the arguments of a function call from left to right. That is why the two `printf()` lines in step 3 give different results.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/01-arithmetic
php -S localhost:8000
```

## Steps

1. Follow along: in `arithmetic.php`, under the comment, add:

   ```php
   $a = 21;
   $b = 2;
   $intDiv = 8 / 2;

   echo '<h5>Arithmetic Operators</h5>';
   printf('<p>%d + %d = %d</p>', $a, $b, $a + $b);
   printf('<p>%d - %d = %d</p>', $a, $b, $a - $b);
   printf('<p>%d * %d = %d</p>', $a, $b, $a * $b);
   printf('<p>%d / %d = %.1f</p>', $a, $b, $a / $b);
   printf('<p>%d %% %d = %.1f</p>', $a, $b, $a % $b);
   printf('<p>%d ** %d = %d</p>', $a, $b, $a ** $b);
   echo ("8 / 2 = $intDiv, which is a " . gettype($intDiv));
   ```

   Visit <http://localhost:8000/arithmetic.php>. *Check: `23`, `19`, `42`, `10.5`, `1.0` and `441`. The last line says `integer`: 8 divides exactly by 2, so the answer is an int.*

2. Change `8 / 2` to `7 / 2` and refresh. *Check: `3.5, which is a double`.* Change it back. Then remove one `%` from `%%` in the modulo line and refresh. *Check: the line now reads `21 %d = 1.0`. PHP read `% %` as one specifier, and the `d` was left over as plain text.* Put it back.

3. Follow along: add identity, negation and the increment operators:

   ```php
   $price = '4.50';
   echo '<h5>Identity & Negation</h5>';
   echo '<p>+$price is a ' . gettype(+$price) . '</p>';
   echo '<p>-$a is ' . -$a . '</p>';

   echo '<h5>Increment & Decrement</h5>';
   $a = 0;
   $b = 0;
   printf('<p>a is %d. After ++a, a is %d</p>', $a, ++$a);
   printf('<p>b is %d. After b++, b is %d</p>', $b, $b++);
   printf('<p>b is now %d</p>', $b);
   ```

   *Check: `+$price` turned the string into a `double`. The `++a` line says `0` then `1`. The `b++` line says `0` then `0`, and only the next line shows `1`.*

4. Your turn: create `minutes.php` in this folder.
   - Set `$totalMinutes = 135;`. Using `/`, `%` and an `(int)` cast from Unit 2, show `135 minutes is 2 hours and 15 minutes` with `printf()`.
   - Show `2 ** 10 = 1024`.
   - Set `$visitors = 0;`, add 1 to it three times with `++`, and show `3 visitors so far`. Then take one away with `--` and show the new number.

   Visit <http://localhost:8000/minutes.php>. *Check: change `$totalMinutes` to `200` and the line says `3 hours and 20 minutes`.*

## Done when

- [ ] `arithmetic.php` shows all six sums, the `integer` line and both increment lines, with no warnings.
- [ ] `minutes.php` converts any number of minutes into hours and minutes, and counts visitors with `++` and `--`.
- [ ] You can say why `printf()` needs `%%` to show `%`.
- [ ] You can say why `$b++` printed `0`.

## Previous / Next

Previous: [Unit 3 overview](../../README.md). Next: [02 Assignment operators](../02-assignment/README.md).
