# Checkpoint 04: Selection

## Goal

Make PHP choose which code to run, with `if`, `elseif`, `else`, the ternary and `switch`.

## Concepts

- A script normally runs from the first line to the last, in order. Control flow statements change that order. They come in two kinds:
  - Selection: run one block of code or another, depending on a condition.
  - Iteration: run a block of code again and again. That is checkpoint 06.
- A condition is a boolean expression: something that works out to `true` or `false`, such as `$age >= 18`.
- `if (condition) { ... } else { ... }` runs the first block when the condition is true, and the `else` block when it is false.
- `elseif` adds more choices. PHP tests them from the top and runs the first one that is true, then skips the rest. The final `else` is the catch-all: it runs when none of the others did.
- The same statement can be written with `:` instead of braces, ending in `endif;`. You will see this style in HTML templates.
- The ternary from checkpoint 03 is a short `if`/`else` for choosing between two values. With more than two choices, an `if` is easier to read than nested ternaries.
- A boolean variable is already true or false, so `if ($isRaining)` does the same job as `if ($isRaining === true)`.
- `switch` compares one value against a list of `case`s. Once a case matches, PHP runs every line below it, through the cases that follow, until it meets `break` or the end. This is called fall-through. `default` is the catch-all.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/04-selection
php -S localhost:8000
```

## Steps

1. Follow along: in `selection.php`, under the comment, add:

   ```php
   echo '<h5>IF Statement</h5>';
   $age = 23;
   if ($age >= 18) {
       echo '<p>You can drive a car.</p>';
   } else {
       echo '<p>You cannot legally drive a car yet.</p>';
   }
   ```

   Visit <http://localhost:8000/selection.php>. *Check: `You can drive a car.`* Change `$age` to `16` and refresh. *Check: the other message.* Change it back.

2. Follow along: offer several choices with `elseif`:

   ```php
   echo '<h5>Multiple Outcomes</h5>';
   $pizzaChoice = 2;
   if ($pizzaChoice === 1) {
       echo '<p>Pizza Margherita</p>';
   } elseif ($pizzaChoice === 2) {
       echo '<p>Pizza Pepperoni</p>';
   } elseif ($pizzaChoice === 3) {
       echo '<p>Pizza Mushroom</p>';
   } else {
       echo '<p>You did not choose an available pizza!</p>';
   }
   ```

   *Check: `Pizza Pepperoni`.* Change `$pizzaChoice` to `5`. *Check: the catch-all message.* Then to `'2'`, a string. *Check: the catch-all again, because `===` checks the type.* Change it back to `2`.

3. Follow along: write the same choice with the alternative syntax:

   ```php
   echo '<h5>Alternative Syntax</h5>';
   if ($pizzaChoice === 1):
       echo '<p>Pizza Margherita</p>';
   elseif ($pizzaChoice === 2):
       echo '<p>Pizza Pepperoni</p>';
   elseif ($pizzaChoice === 3):
       echo '<p>Pizza Mushroom</p>';
   else:
       echo '<p>You did not choose an available pizza!</p>';
   endif;
   ```

   *Check: `Pizza Pepperoni` again.*

4. Follow along: choose a message with a ternary, then with an `if`:

   ```php
   echo '<h5>Ternary</h5>';
   $isRaining = true;
   $message = $isRaining === true ? 'Better take an umbrella!' : 'Enjoy the day out!';
   echo "<p>$message</p>";

   echo '<h5>Boolean Values</h5>';
   $message = null;
   if ($isRaining === true) {
       $message = 'Better take an umbrella!';
   } else {
       $message = 'Enjoy the day out!';
   }
   echo "<p>$message</p>";
   ```

   *Check: the umbrella message, twice.* `$isRaining === true` is true exactly when `$isRaining` is true, so the comparison adds nothing. Delete ` === true` from both places and refresh. *Check: the page has not changed.*

5. Follow along: try a `switch` with no `break`:

   ```php
   echo '<h5>Switch</h5>';
   $pizzaChoice = 2;
   switch ($pizzaChoice) {
       case 1: echo '<p>Pizza Margherita</p>';
       case 2: echo '<p>Pizza Pepperoni</p>';
       case 3: echo '<p>Pizza Mushroom</p>';
       default: echo '<p>You did not choose an available pizza!</p>';
   }
   ```

   *Check: three lines, Pepperoni, Mushroom and the catch-all. Case 2 matched and PHP fell through everything below it.*

   Now replace that `switch` with one that stops each case with `break`:

   ```php
   switch ($pizzaChoice) {
       case 1:
           echo '<p>Pizza Margherita</p>';
           break;
       case 2:
           echo '<p>Pizza Pepperoni</p>';
           break;
       case 3:
           echo '<p>Pizza Mushroom</p>';
           break;
       default:
           echo '<p>You did not choose an available pizza!</p>';
   }
   ```

   *Check: only `Pizza Pepperoni`.*

6. Follow along: sometimes fall-through is what you want. A log level switches on its own messages and every more serious level. The cases go from least to most serious, so the matching case falls through to all the serious ones below it:

   ```php
   echo '<h5>Switch Fall-Through</h5>';
   $logError = false;
   $logWarning = false;
   $logNotice = false;
   $logInfo = false;
   $logDebug = false;

   $logLevel = 'NOTICE';
   switch ($logLevel) {
       case 'DEBUG':
           $logDebug = true;
       case 'INFO':
           $logInfo = true;
       case 'NOTICE':
           $logNotice = true;
       case 'WARN':
           $logWarning = true;
       case 'ERR':
           $logError = true;
       default:
           showLogs($logError, $logWarning, $logNotice, $logInfo, $logDebug);
   }

   function showLogs($logError = false, $logWarning = false, $logNotice = false, $logInfo = false, $logDebug = false)
   {
       // Critical, Alert and Emergency are always logged
       echo '<p>Logging: CRITICAL, ALERT, EMERGENCY</p>';
       echo '<ul>';
       echo '<li>ERR: ' . ($logError ? 'on' : 'off') . '</li>';
       echo '<li>WARN: ' . ($logWarning ? 'on' : 'off') . '</li>';
       echo '<li>NOTICE: ' . ($logNotice ? 'on' : 'off') . '</li>';
       echo '<li>INFO: ' . ($logInfo ? 'on' : 'off') . '</li>';
       echo '<li>DEBUG: ' . ($logDebug ? 'on' : 'off') . '</li>';
       echo '</ul>';
   }
   ```

   The five `false` lines at the top are not on the slide. Without them, the variables above the matching case would not exist, and PHP would warn you when they are passed to `showLogs()`.

   *Check: ERR, WARN and NOTICE are on, INFO and DEBUG are off.* Change `$logLevel` to `'DEBUG'`, then `'ERR'`, and check the list each time.

7. Your turn: create `grades.php` in this folder.
   - Set `$mark = 72;`. Using `if`, `elseif` and `else`, store a grade in `$grade`: `A` for 80 or more, `B` for 65 or more, `C` for 50 or more, otherwise `Fail`. Show `A mark of 72 gets grade B.`
   - Using a ternary, show `You passed.` for 50 or more and `You failed.` otherwise.
   - Set `$day = 'Saturday';`. Using one `switch`, show the shop's opening hours: Saturday and Sunday share one message (use fall-through: two `case` lines, one block), Friday has its own, and every other day uses `default`.

   Visit <http://localhost:8000/grades.php>. *Check: try marks of `80`, `65`, `50` and `49`, and days `Sunday`, `Friday` and `Monday`.*

## Done when

- [ ] `selection.php` shows every section from steps 1 to 6, with no warnings.
- [ ] `grades.php` gives the right grade at each boundary, and the right hours for every day.
- [ ] You can say why the first `switch` in step 5 printed three lines.
- [ ] You can say why `if ($isRaining)` is enough.

## Previous / Next

Previous: [03 Comparison and conditional operators](../03-comparison/README.md). Next: [05 Logical operators](../05-logical/README.md).
