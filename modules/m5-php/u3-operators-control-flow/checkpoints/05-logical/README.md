# Checkpoint 05: Logical operators

## Goal

Make a decision that depends on more than one condition, by joining the conditions with `&&`, `||`, `xor` and `!`.

## Concepts

Each logical operator takes true-or-false inputs and gives one true-or-false output:

| A | B | `A && B` (and) | `A \|\| B` (or) | `A xor B` |
|---|---|---|---|---|
| false | false | false | false | false |
| false | true | false | true | true |
| true | false | false | true | true |
| true | true | true | true | false |

- `&&` is true only when every input is true.
- `||` is true when at least one input is true.
- `xor` is true when exactly one input is true.
- `!` takes one input and flips it: `!true` is `false`.
- You can combine them. Use brackets to make the order obvious: `(A || B) && C`.
- `!` is applied before comparisons such as `<`. `!$x < 60` means `(!$x) < 60`, not `!($x < 60)`. When you want to negate a comparison, put the comparison in brackets.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/05-logical
php -S localhost:8000
```

## Steps

1. Follow along: the coffee machine needs enough beans and enough water. In `coffee.php`, under the comment, add:

   ```php
   echo '<h5>AND</h5>';
   $beanWeight = 50;
   $waterWeight = 200;

   if ($beanWeight > 30 && $waterWeight > 150) {
       echo '<p>Coffee machine working...</p>';
   } else {
       echo '<p>Error: check coffee beans and water amount.</p>';
   }
   ```

   Visit <http://localhost:8000/coffee.php>. *Check: working.* Change `$waterWeight` to `100`. *Check: the error. One false input is enough.* Change it back.

2. Follow along: beans or ground coffee will do:

   ```php
   echo '<h5>OR</h5>';
   $grindWeight = 30;
   if ($beanWeight > 30 || $grindWeight > 20) {
       echo '<p>Coffee machine working...</p>';
   } else {
       echo '<p>Error: check coffee beans/grind and water amount.</p>';
   }
   ```

   *Check: working.* Set `$beanWeight` to `0`. *Check: still working, thanks to the grind.* Change it back to `50`.

3. Follow along: beans or grind, and water as well:

   ```php
   echo '<h5>Combined</h5>';
   if (($beanWeight > 30 || $grindWeight > 20) && $waterWeight > 150) {
       echo '<p>Coffee machine working...</p>';
   } else {
       echo '<p>Error: check coffee beans/grind and water amount.</p>';
   }
   ```

   *Check: working.* Set `$waterWeight` to `100`. *Check: the AND and Combined sections fail, the OR section still works.* Change it back.

4. Follow along: the coffee valve and the steam valve must not be open at the same time:

   ```php
   echo '<h5>XOR</h5>';
   $coffeeValveState = 'Open';
   $steamValveState = 'Closed';
   if ($coffeeValveState === 'Open' xor $steamValveState === 'Open') {
       echo '<p>Coffee machine working...</p>';
   } else {
       echo '<p>Error: please close steam valve or coffee valve.</p>';
   }
   ```

   *Check: working.* Set both valves to `'Open'`, then both to `'Closed'`. *Check: the error both times.* Put them back.

5. Follow along: the slides show `!` with this example:

   ```php
   echo '<h5>NOT</h5>';
   $waterTemperature = 40;
   if (!$waterTemperature < 60) {
       echo '<p>Status: Heating...</p>';
   } else {
       echo '<p>Ready to make coffee.</p>';
   }
   ```

   *Check: `Heating...`.* Now change `$waterTemperature` to `70`. *Check: still `Heating...`, which is wrong.* `!` is applied to `$waterTemperature` first, so `!70` is `false`, and `false < 60` is true. Fix the condition so that `!` flips the whole comparison:

   ```php
   if (!($waterTemperature >= 60)) {
   ```

   *Check: `Ready to make coffee.` at 70, and `Heating...` at 40.* Leave it at 40.

6. Your turn: create `menu.php` in this folder for a restaurant. Start with:

   ```php
   $wantsStarter = true;
   $wantsDessert = false;
   $hasBooking = false;
   $isMember = true;
   $isKitchenOpen = true;
   ```

   Then add four `if` statements, using a different operator in each of the first three:
   - Guests with a booking or a membership get `Welcome, please take a seat.`, everyone else `Sorry, members and bookings only tonight.`
   - The set menu comes with a starter or a dessert, but not both: `Order accepted.` or `The set menu comes with a starter or a dessert, but not both.`
   - If the kitchen is not open, show `The kitchen is closed.`, otherwise `The kitchen is open.`. Use `!`.
   - If the guest is allowed in and the kitchen is open, show `Your order is on its way.`. Combine the operators, with brackets.

   Visit <http://localhost:8000/menu.php>. *Check: change each variable in turn and make sure every message still makes sense.*

## Done when

- [ ] `coffee.php` shows the five sections, and the NOT section is correct at both 40 and 70 degrees.
- [ ] `menu.php` uses `||`, `xor`, `!` and a combined condition with brackets.
- [ ] You can say why `!$waterTemperature < 60` was true at 70 degrees.
- [ ] You can say when `xor` gives a different answer from `||`.

## Previous / Next

Previous: [04 Selection](../04-selection/README.md). Next: [06 Loops](../06-loops/README.md).
