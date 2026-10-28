# Checkpoint 05: Inspecting variables

## Goal

Look inside any variable to find out exactly what it holds, and test whether a variable exists or is empty.

## Concepts

When a page shows the wrong thing, the first question is usually "what is really in this variable?". `echo` answers it for a string or a number, but not for an array or an object. PHP has three functions that show anything:

| Function | Shows |
|---|---|
| `print_r($x)` | The values, in a form that is easy to read. |
| `var_dump($x)` | Each value's type, and the length of each string. It accepts several variables at once: `var_dump($a, $b)`. |
| `var_export($x)` | The value written as PHP code, which you could paste into a file. |

- All three lay out their output over several lines. Wrap them in `<pre>` so the browser keeps the layout.
- `$booking['guest']` reads the value stored under the key `guest` in the array `$booking`. You will learn more about arrays in Unit 6.
- `isset($x)` is true when `$x` exists and is not `null`.
- `empty($x)` is true when `$x` does not exist, or holds a falsy value from checkpoint 02: `''`, `0`, `0.0`, `'0'`, `null`, `false` or an empty array.
- Neither function warns you when the variable does not exist. That is what they are for.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/05-inspecting-variables
php -S localhost:8000
```

## Steps

1. Follow along: in `inspect.php`, create an array that mixes types, and try to show it with `echo`:

   ```php
   $sample = ['Joe', 12, 11.23, true, new stdClass()];
   echo $sample;
   ```

   Visit <http://localhost:8000/inspect.php>. *Check: "Array to string conversion".* `stdClass` is PHP's built-in empty class. It gives you an object without writing a class.

2. Replace the `echo` line with `print_r($sample);`. *Check: all five values on one long line, hard to read.* Now wrap it:

   ```php
   echo '<pre>';
   print_r($sample);
   echo '</pre>';
   ```

   *Check: one value per line.* `print_r()` shows `true` as `1`.

3. Follow along: add `var_dump()` and `var_export()` inside the same `<pre>`:

   ```php
   echo '<pre>';
   print_r($sample);
   var_dump($sample);
   var_export($sample);
   echo '</pre>';
   ```

   *Check: `var_dump()` shows `string(3) "Joe"`, `int(12)`, `float(11.23)` and `bool(true)`. `var_export()` shows `array (` and `true`, the way you would type them in PHP.*

4. Follow along: test four variables with `isset()` and `empty()`:

   ```php
   $firstVar = 42;
   $secondVar = null;
   $fourthVar = 0;

   echo '<h2>isset()</h2>';
   echo '<p>First: ' . (isset($firstVar) ? 'set' : 'not set') . '</p>';
   echo '<p>Second: ' . (isset($secondVar) ? 'set' : 'not set') . '</p>';
   echo '<p>Third: ' . (isset($thirdVar) ? 'set' : 'not set') . '</p>';
   echo '<p>Fourth: ' . (isset($fourthVar) ? 'set' : 'not set') . '</p>';

   echo '<h2>empty()</h2>';
   echo '<p>First: ' . (empty($firstVar) ? 'empty' : 'not empty') . '</p>';
   echo '<p>Second: ' . (empty($secondVar) ? 'empty' : 'not empty') . '</p>';
   echo '<p>Third: ' . (empty($thirdVar) ? 'empty' : 'not empty') . '</p>';
   echo '<p>Fourth: ' . (empty($fourthVar) ? 'empty' : 'not empty') . '</p>';
   ```

   `$thirdVar` was never created. *Check: no warning about it. The fourth variable is set, and also empty.*

5. Your turn: `booking.php` holds a booking that has just come in. Work in the `<body>`, under the heading.
   - Show the whole booking with `var_dump()`, inside `<pre>`.
   - One value looks like a number but is stored as a string. Under the dump, add a paragraph that names its key. Then show that value cast to an `int` with `var_dump()`, so that you can see the type change.
   - Add a table with three columns: Key, `isset()` and `empty()`. Give it a row each for `notes`, `promoCode`, `breakfast`, `nights` and `roomNumber`, using `isset($booking['notes'])` and so on. There is no `roomNumber` in the booking. Predict each answer before you refresh.
   - Finally, show the booking with `var_export()`, inside `<pre>`.

   Visit <http://localhost:8000/booking.php>. *Check: `promoCode` and `roomNumber` are the only ones that are not set, and `nights` is the only one that is not empty.*

## Done when

- [ ] `inspect.php` shows the sample array with all three functions, and the `isset()` and `empty()` results.
- [ ] `booking.php` shows the dump, names the value stored as a string, and has the `isset()` and `empty()` table with no warnings.
- [ ] You can say which function you would use to find out whether a value is the number `4` or the string `'4'`.
- [ ] You can say why `$fourthVar` is set but empty.

## Previous / Next

Previous: [04 Formatting output](../04-formatting-output/README.md). Next: [06 Footer and PHP Summary](../06-footer-summary/README.md).
