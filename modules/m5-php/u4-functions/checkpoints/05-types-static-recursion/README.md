# Checkpoint 05: Types, static variables and recursion

## Goal

Say what types a function expects and returns, keep a value between calls, and write a function that calls itself.

## Concepts

- A type hint, or type declaration, goes in front of a parameter: `string $currency`. A return type goes after the brackets: `): string`. Together they tell anyone using the function what to pass and what comes back, without reading its body.
- Your editor reads type hints too. With the PHP extension in VS Code, hovering over a call shows each parameter's type, and a wrong argument is underlined.
- By default, PHP converts a value to the declared type when it can. A `bool` parameter turns `'yes'` into `true`, and a `float` parameter turns `40` into `40.0`. Values that cannot be converted, such as an array for a `string`, stop the script with a `TypeError`.
- `declare(strict_types=1);` as the very first line of a file turns the conversion off for calls made in that file. Then a wrong type is a `TypeError`. The only exception: an `int` is still accepted where a `float` is expected.
- The `array` type only says "some array". It cannot say what the array holds. Classes, in Unit 7, give you a way round that.
- `void` as a return type means the function returns nothing. `int|string` means it returns either an `int` or a `string`. That is a union type.
- A function's local variables are normally created fresh on every call. A `static` variable is created on the first call and keeps its value for the next ones. It is still local: only that function can see it.
- A recursive function calls itself. It suits data that contains smaller copies of itself, such as arrays inside arrays, or folders inside folders. Every recursive function needs a case where it stops calling itself, here a value that is not an array.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u4-functions/checkpoints/05-types-static-recursion
php -S localhost:8000
```

## Steps

1. Follow along: in `advanced.php`, under the comment, add a function without type hints:

   ```php
   echo '<h5>Type Hinting</h5>';
   function showCartTotal($cartItems, $currency, $rightPlacement, $freeThreshold)
   {
       $cartTotal = array_sum($cartItems);
       $formattedTotal = number_format($cartTotal, 2, '.', ',');
       $result = '<p>Your total is <strong>' . ($rightPlacement ? $formattedTotal . $currency : $currency . $formattedTotal) . '</strong></p>';
       if ($cartTotal > $freeThreshold) {
           $result .= '<p>Your cart qualifies for free delivery.</p>';
       }
       return $result;
   }

   echo showCartTotal([12.18, 3.45, 11.80, 12.65], '€', false, 40);
   ```

   Visit <http://localhost:8000/advanced.php>. *Check: `Your total is €40.08` and the free delivery line.* Now start typing a second call, `echo showCartTotal(`, and look at the hint your editor shows. *Check: every parameter is `mixed`, so the hint does not help.* Delete the half-written line.

2. Follow along: change the function's header to:

   ```php
   function showCartTotal(array $cartItems, string $currency, bool $rightPlacement, float $freeThreshold): string
   ```

   Start typing `echo showCartTotal(` again. *Check: the hint now shows each type, and the return type `string`.*

   Add a call with a mistake in it:

   ```php
   echo showCartTotal([23, 45, 67], '€', 'yes', 40);
   ```

   *Check: your editor underlines `'yes'`. The page still works, and shows `Your total is 135.00€`.* PHP turned `'yes'` into `true`, so the currency went on the right.

3. Follow along: make PHP strict. Change the first line of the file from `<?php` to:

   ```php
   <?php declare(strict_types=1);
   ```

   Refresh. *Check: the first total, then a fatal `TypeError`: argument 3 must be of type bool, string given.* Strict mode does not convert `'yes'`. It still accepts `40` for a `float`, because every `int` is also a valid number with decimals. That is the one conversion strict mode allows. Change `'yes'` to `true`. *Check: both totals show again.*

4. Follow along: count calls with a normal local variable, then with a static one:

   ```php
   echo '<h5>Static Variables</h5>';
   function sayHello(string $user): void
   {
       $callCount = 0;
       $callCount++;
       echo "<p>Hello, $user. Function has been called $callCount times.</p>";
   }

   sayHello('Bob');
   sayHello('Alice');
   sayHello('Terence');
   ```

   *Check: `1 times` on all three lines.* Change `$callCount = 0;` to `static $callCount = 0;`. *Check: `1`, `2` and `3`.*

5. Follow along: show nested results with recursion:

   ```php
   echo '<h5>Recursion</h5>';
   function showResults(array $results): void
   {
       echo '<ul>';
       foreach ($results as $key => $value) {
           if (is_array($value)) {
               echo "<li>$key:</li>";
               showResults($value);
           } else {
               echo "<li>$key: $value</li>";
           }
       }
       echo '</ul>';
   }

   $results = [
       'English' => 90,
       'Maths' => [
           'Sit 1' => 61,
           'Sit 2' => 78,
       ],
       'History' => 76,
       'Chemistry' => [
           'Sit 1' => [
               'Theory' => 87,
               'Practical' => 48,
           ],
           'Sit 2' => [
               'Theory' => 90,
               'Practical' => 65,
           ],
       ],
   ];
   showResults($results);
   ```

   *Check: a list three levels deep, with Theory and Practical inside Chemistry's sits.* Add one more level anywhere, for example split English into two sits. *Check: it is shown with no change to the function.*

6. Your turn: create `tree.php` in this folder, starting with `<?php declare(strict_types=1);`.
   - Copy `$results` from step 5.
   - Write `countMarks(array $results): int`, which returns how many marks there are at every level. It is recursive: for an array, add up `countMarks()` of the inner array; for a mark, count 1.
   - Write `averageMark(array $results): float`, which returns the average of every mark. You will need a second recursive function, `sumMarks(array $results): int`.
   - Show `There are 8 marks, with an average of 74.4.`, using `number_format()` for the average.

   Visit <http://localhost:8000/tree.php>. *Check: 8 marks, average 74.4. Add a mark anywhere in `$results` and both numbers change.*

## Done when

- [ ] `advanced.php` shows every section from steps 1 to 5, with strict types on and no errors.
- [ ] `tree.php` counts and averages the marks at every level.
- [ ] You can say what PHP does with `'yes'` for a `bool` parameter, with and without strict types.
- [ ] You can say where `showResults()` stops calling itself.

## Previous / Next

Previous: [04 Functions as values](../04-functional/README.md). Next: [06 Intro Website: folders, DNS and Mastodon](../06-intro-website/README.md).
