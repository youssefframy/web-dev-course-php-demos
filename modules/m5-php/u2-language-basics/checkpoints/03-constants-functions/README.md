# Checkpoint 03: Constants and functions

## Goal

Store values that must never change as constants, and write your first function.

## Concepts

- A constant is a name for a value, like a variable, but once it has its value it cannot be changed.
- Constant names have no `$`. By convention they are written in capitals, with underscores between words: `VAT_RATE`.
- There are two ways to create one:

  ```php
  define('VAT_RATE', 0.18);
  const CAFE_NAME = 'Kafè Kordin';
  ```

  They differ in where they are allowed, which matters once you meet `if` in Unit 3. In this unit, either will do.
- A constant holds a scalar value or an array.
- A function is a named piece of code that you can run as often as you like. `function` creates it, and `return` sends a value back to the code that ran, or called, it:

  ```php
  function getGreeting()
  {
      return 'Merħba!';
  }

  echo getGreeting();
  ```

  You will learn much more about functions in Unit 4.
- A variable function is a variable that holds a function's name. Add `()` to the variable to run the function: `$greet = 'getGreeting'; echo $greet();`. To use it inside a double-quoted string, wrap it in braces: `"{$greet()}"`.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/03-constants-functions
php -S localhost:8000
```

## Steps

1. Follow along: in `constants.php`, create a constant with `define()` and show it:

   ```php
   define('VAT_RATE', 0.18);
   echo '<p>VAT in Malta is charged at ' . VAT_RATE . '</p>';
   ```

   Visit <http://localhost:8000/constants.php>. *Check: `0.18`.*

   A constant cannot go inside a double-quoted string the way a variable can. It has no `$` for PHP to spot, so you have to use `.` to join it.

2. Under it, try to change the constant:

   ```php
   define('VAT_RATE', 0.05);
   echo '<p>VAT is now ' . VAT_RATE . '</p>';
   ```

   *Check: a warning that `VAT_RATE` is already defined, and the second paragraph still says `0.18`.* Remove both lines.

3. Follow along: a constant can also hold an array, and you can make one with `const`:

   ```php
   define('SIZES', ['Small', 'Medium', 'Large']);
   echo '<p>Cup sizes:</p>';
   print_r(SIZES);

   const CAFE_NAME = 'Kafè Kordin';
   echo '<h1>' . CAFE_NAME . '</h1>';
   ```

4. Follow along: write a function, then call it:

   ```php
   function getGreeting()
   {
       return 'Merħba!';
   }

   echo '<p>' . getGreeting() . '</p>';
   ```

   The function's opening brace goes on a line of its own. That is the PSR-12 rule for functions. Format Document does it for you.

5. Follow along: call the same function through a variable:

   ```php
   $greet = 'getGreeting';
   echo "<p>{$greet()} Welcome to our cafe.</p>";
   ```

   *Check: the greeting appears in the sentence. Remove the braces and refresh: the function name appears instead. Put the braces back.*

6. Your turn: create `pricelist.php` for a cafe of your own.
   - Use `const` for the cafe's name and the year it opened, and `define()` for `VAT_RATE`, set to `0.18`.
   - Write a function `getOpeningHours()` that returns the opening hours as a string, for example `'07:00 to 19:00'`.
   - Create two variables for prices before VAT: `$espressoPrice = 1.60;` and `$croissantPrice = 2.80;`.
   - Show an `<h1>` with the cafe's name, then `Open since 2009, 07:00 to 19:00`, using the constant and the function.
   - Show each item's price including VAT, worked out as the price times `(1 + VAT_RATE)`. Store each result in a variable first, for example `$croissantWithVat`.

   *Check: the croissant costs `3.304`. Nobody pays in thousandths of a euro. You fix this in the next checkpoint.*

## Done when

- [ ] `constants.php` shows the VAT rate, the cup sizes, the cafe's name and two greetings.
- [ ] `pricelist.php` uses two `const` constants, one `define()` and your own function.
- [ ] You can say what happened when you tried to change `VAT_RATE`.
- [ ] You can say why `"VAT is VAT_RATE"` does not show the rate.

## Previous / Next

Previous: [02 Data types](../02-data-types/README.md). Next: [04 Formatting output](../04-formatting-output/README.md).
