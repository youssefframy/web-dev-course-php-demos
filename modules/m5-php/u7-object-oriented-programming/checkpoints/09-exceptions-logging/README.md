# Checkpoint 09: Exceptions and logging

## Goal

Handle bad input without a crash, tell the visitor what went wrong in plain words, and keep a record of the problems you did not expect.

## Concepts

- When something goes wrong, PHP *throws* an exception or an error. Unhandled, it stops the page and prints a message.
- `try { ... } catch (Type $e) { ... }` runs the code in `try`, and if it throws, jumps to the first `catch` whose type matches.
- PHP has two families. `Exception` is what you throw yourself with `throw new Exception('message')`. `Error` is what PHP throws: `TypeError`, `DivisionByZeroError` and others. A `catch (Exception $e)` does **not** catch an `Error`. Both implement `Throwable`.
- Put the most specific `catch` first, and the general ones last.
- Some problems are the visitor's fault (dividing by zero). Tell them. Others are yours (a bug). Do not show those, but record them.
- `error_log($message, 3, $file)` appends a message to a file. Type `0` sends it to the server's log, `1` emails it, `3` appends to a file, `4` goes to the SAPI logger.
- `set_error_handler()` and `set_exception_handler()` register functions that PHP calls for anything you did not catch.
- A form sends everything as text. A function with a `float` parameter accepts `'10'`, but refuses `''` and `'abc'` with a `TypeError`. This works only because the file does not use `strict_types`.

## Before you start

You need `index.php` and `lib/` from checkpoint 08. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/08-traits/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add the function and the form handling to the PHP block at the top of `index.php`, below your classes.

   ```php
   function simpleDivision(float $num1, float $num2): float
   {
       return $num1 / $num2;
   }

   $divResult = null;
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $action = filter_input(INPUT_POST, 'action');
       switch ($action) {
           case 'divide':
               $num1 = filter_input(INPUT_POST, 'num1');
               $num2 = filter_input(INPUT_POST, 'num2');
               $divResult = (string) simpleDivision($num1, $num2);
               break;
       }
   }
   ```

   The slides declare `global $divResult` here, which does nothing outside a function, so leave it out. Add the form at the bottom of the page.

   ```php
   <h3>Simple Division</h3>
   <?php if ($divResult !== null) { ?>
       <p><strong><?= htmlspecialchars($divResult) ?></strong></p>
   <?php } ?>
   <form name="divideForm" method="POST">
       <input type="hidden" name="action" value="divide">
       <label for="num1">Num 1</label>
       <input type="text" name="num1" id="num1">
       <label for="num2">Num 2</label>
       <input type="text" name="num2" id="num2">
       <input type="submit" value="Divide">
   </form>
   ```

   *Check: 10 and 4 gives `2.5`.* Now try 10 and 0, 10 and `abc`, and two empty fields. *Check: each one stops the page with a fatal error.*

2. Follow along: wrap the call.

   ```php
   try {
       $divResult = (string) simpleDivision($num1, $num2);
   } catch (\Exception $e) {
       $divResult = $e->getMessage();
   }
   ```

   *Check: 10 and 0 still crashes.* Division by zero throws a `DivisionByZeroError`, and bad input throws a `TypeError`. Both are `Error`s. The `catch` for `Exception` does not see them.

3. Follow along: catch the specific ones first.

   ```php
   try {
       $divResult = (string) simpleDivision($num1, $num2);
   } catch (\DivisionByZeroError $e) {
       $divResult = 'You cannot divide by zero. Please ensure Num 2 is not 0.';
   } catch (\TypeError $e) {
       $divResult = 'Please ensure both fields are filled in and numeric.';
   } catch (\Exception $e) {
       $divResult = $e->getMessage();
   } catch (\Error $e) {
       $divResult = $e->getMessage();
   }
   ```

   *Check: all three bad inputs from step 1 now show a friendly message.* The backslashes are needed because the file is in the `unit7` namespace.

4. Follow along: log the unexpected ones. Define a log folder near the top of the PHP block, and write to it in the last two `catch` blocks.

   ```php
   const LOG_DIR = __DIR__ . '/logs';
   if (!is_dir(LOG_DIR)) {
       mkdir(LOG_DIR, 0755, true);
   }
   ```

   ```php
   } catch (\Exception $e) {
       $divResult = $e->getMessage();
       error_log($e->getMessage() . "\n" . $e->getTraceAsString() . "\n", 3, LOG_DIR . '/exception.log');
   } catch (\Error $e) {
       $divResult = $e->getMessage();
       error_log($e->getMessage() . "\n" . $e->getTraceAsString() . "\n", 3, LOG_DIR . '/error.log');
   }
   ```

   The slides log to `/var/log/mysite`, which does not exist in the dev container and needs special permissions. A folder next to the page works anywhere. Type `3` writes exactly what you give it, so end each message with a newline. `getTraceAsString()` lists the calls that led to the problem.

5. Follow along: catch everything else with global handlers, above the function.

   ```php
   set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
       error_log(sprintf("Error in %s on line %d. %d: %s\n", $file, $line, $severity, $message), 3, LOG_DIR . '/error.log');
       return false; // let PHP's own handling carry on as well
   });
   set_exception_handler(function (\Throwable $exception) {
       error_log($exception->getMessage() . "\n" . $exception->getTraceAsString() . "\n", 3, LOG_DIR . '/exception.log');
       if (!headers_sent()) {
           http_response_code(500);
       }
       echo 'Something went wrong. Please try again later.';
   });
   ```

   The slides call `printf()` where `sprintf()` is needed (`printf` prints and returns a length), and write `$s` where `%s` is meant. Both are fixed above.

6. Your turn: add `echo $neverSet;` somewhere in the page, then reload. *Check: a warning on the page, and a line in `logs/error.log` naming the file and line.* Remove it, then add `throw new \RuntimeException('boom');` at the end of the PHP block. *Check: the page shows the plain message, and `logs/exception.log` has `boom` and a trace.* Remove it, and delete the `logs/` folder. It is ignored by Git.

## Done when

- [ ] 10 and 0, 10 and `abc`, and empty fields each show a friendly message.
- [ ] You can say why `catch (Exception $e)` missed the `DivisionByZeroError`.
- [ ] Problems you did not expect end up in a log file.
- [ ] You can say why the more specific `catch` blocks come first.

## Previous / Next

Previous: [08 Traits](../08-traits/README.md). Next: [10 Reflection](../10-reflection/README.md).
