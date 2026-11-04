# Checkpoint 02: Writing functions

## Goal

Write your own functions, pass values into them, and get a result back.

## Concepts

- So far each page has been one long script, run from top to bottom. A function is a block of code with a name. You write it once and run it, or call it, as often as you like.
- A function takes input, does some work, and can give back output. The input values are the parameters, listed in brackets after the name. The output is sent back with `return`.
- Strictly, a parameter is the name the function uses, such as `$num1`, and an argument is the value you pass when you call it, such as `5`. Many people use the two words for the same thing.
- Other languages call functions routines, subroutines, procedures or methods. In PHP, a method is a function that belongs to a class. You will meet those in Unit 7.
- By default, PHP passes a copy of each argument. Changing the parameter inside the function does not change the variable you passed in. This is passing by value.
- With `&` before a parameter, the function receives the variable itself instead of a copy, so changes inside the function are kept. This is passing by reference. You saw `&` with variables in Unit 3.
- A parameter can have a default value, such as `$power = 2`. If the caller leaves that argument out, PHP uses the default. Parameters with defaults must come after the ones without.
- A function can take any number of arguments. `...$nums` collects them all into an array called `$nums`. This is a variadic parameter. The older `func_get_args()` does the same job, but the function's header no longer shows that it takes arguments.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u4-functions/checkpoints/02-functions
php -S localhost:8000
```

## Steps

1. Follow along: in `functions.php`, under the comment, add:

   ```php
   echo '<h5>A First Function</h5>';
   function add($num1, $num2)
   {
       return $num1 + $num2;
   }

   $result = add(5, 7);
   echo "<p>The result is $result</p>";
   ```

   Visit <http://localhost:8000/functions.php>. *Check: `The result is 12`.* The `5` lands in `$num1` and the `7` in `$num2`. `return` sends back `12`, which takes the place of `add(5, 7)` and is stored in `$result`.

2. Follow along: pass by value:

   ```php
   echo '<h5>By Value</h5>';
   function add10($num)
   {
       $num += 10;
   }

   $a = 4;
   echo "<p>Value before function call: $a</p>";
   add10($a);
   echo "<p>Value after function call: $a</p>";
   ```

   *Check: `4` both times.* `$num` was a copy of `$a`, so adding 10 to it did not touch `$a`.

3. Follow along: pass by reference, with `&` in front of the parameter:

   ```php
   echo '<h5>By Reference</h5>';
   function add20(&$num)
   {
       $num += 20;
   }

   $b = 4;
   echo "<p>Value before function call: $b</p>";
   add20($b);
   echo "<p>Value after function call: $b</p>";
   ```

   *Check: `4`, then `24`.* `$num` and `$b` are now the same variable under two names.

4. Follow along: give a parameter a default value:

   ```php
   echo '<h5>Default Parameters</h5>';
   function raiseToPower($num, $power = 2)
   {
       return pow($num, $power);
   }

   printf('<p>Optional argument is specified: %d</p>', raiseToPower(10, 5));
   printf('<p>Optional argument is not specified: %d</p>', raiseToPower(10));
   ```

   *Check: `100000`, then `100`.* `pow()` is one of PHP's built-in functions. You call it the same way as your own.

5. Follow along: see why defaults go last. Change the header of `raiseToPower()` to:

   ```php
   function raiseToPower($power = 2, $num)
   ```

   Refresh. *Check: a deprecation notice about the optional parameter, a wrong answer of `9765625` for the first call, then a fatal `ArgumentCountError` for `raiseToPower(10)`.* PHP fills parameters from the left. In the first call the `10` went into `$power` and the `5` into `$num`. In the second, the `10` went into `$power` and nothing was left for `$num`. Change the header back.

6. Follow along: accept any number of arguments, first with `func_get_args()`:

   ```php
   echo '<h5>Variable Parameters</h5>';
   function sum()
   {
       $total = 0;
       foreach (func_get_args() as $num) {
           $total += $num;
       }
       return $total;
   }

   printf('<p>Adding numbers together: %d</p>', sum(10, 87, 42, 56));
   ```

   Then with a variadic parameter:

   ```php
   function sumVariadic(...$nums)
   {
       $total = 0;
       foreach ($nums as $num) {
           $total += $num;
       }
       return $total;
   }

   printf('<p>Adding numbers together: %d</p>', sumVariadic(10, 87, 42, 56));
   ```

   *Check: `195` twice.* Both work, but only the header of `sumVariadic()` tells you it takes numbers. Use the variadic form.

7. Your turn: create `shop.php` in this folder, with three functions.
   - `formatPrice($amount, $currency = '€')` returns the amount with two decimals and the currency in front, for example `€12.50`. Use `number_format()`.
   - `average(...$numbers)` returns the average of all the numbers it receives. `count()` tells you how many there are.
   - `applyDiscount(&$price, $percent)` takes a percentage off `$price` itself and returns nothing.

   Then set `$price = 80;`, show it with `formatPrice()`, call `applyDiscount($price, 25)`, and show it again. Also show the average of `4, 8, 15, 16, 23, 42`, and `formatPrice(9.5, '$')`.

   Visit <http://localhost:8000/shop.php>. *Check: `€80.00`, then `€60.00`, an average of `18`, and `$9.50`.*

## Done when

- [ ] `functions.php` shows every section from steps 1 to 6, with no warnings or errors.
- [ ] `shop.php` shows the four results from step 7.
- [ ] You can say what changes when you put `&` in front of a parameter.
- [ ] You can say why parameters with defaults must come last.

## Previous / Next

Previous: [01 Dark mode from the system setting](../01-system-darkmode/README.md). Next: [03 Return values and scope](../03-scope/README.md).
