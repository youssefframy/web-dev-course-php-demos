# Checkpoint 04: Formatting output

## Goal

Control exactly how values appear on the page: decimal places, padding and capital letters.

## Concepts

- `print('...')` shows text, just like `echo`. The differences are small. `echo` is part of the language and takes several values separated by commas. `print()` is a function, takes one value, and returns `1`. This course uses `echo`.
- `printf()` prints a format string with placeholders, called format specifiers. Each `%` placeholder is replaced by the next value in the list, in order:

  ```php
  printf('<p>The %s ferry leaves at %s.</p>', 'Gaudos', '10:45');
  ```

- A specifier ends with a letter that says how to show the value. Options go between the `%` and that letter.

  | Specifier | Shows | Example | Output |
  |---|---|---|---|
  | `%s` | a string | `printf('%s', 'Hi')` | `Hi` |
  | `%d` | a whole number | `printf('%d', 42)` | `42` |
  | `%+d` | a whole number, always with its sign | `printf('%+d', 42)` | `+42` |
  | `%05d` | padded with zeros to 5 characters | `printf('%05d', 42)` | `00042` |
  | `%.2f` | a float, rounded to 2 places | `printf('%.2f', 3.304)` | `3.30` |
  | `%8.2f` | the same, padded with spaces to 8 characters | `printf('%8.2f', 3.304)` | `    3.30` |
  | `%10s` | padded on the left to 10 characters | `printf('%10s', 'Tea')` | `       Tea` |
  | `%-10s` | padded on the right instead | `printf('%-10s', 'Tea')` | `Tea       ` |
  | `%'*10s` | padded with `*` instead of spaces | `printf("%'*10s", 'Tea')` | `*******Tea` |
  | `%.2s` | cut to 2 characters | `printf('%.2s', 'Hello')` | `He` |

  `%x` (hexadecimal), `%o` (octal), `%b` (binary), `%e` (scientific) and `%c` (a character from its code) also exist.
- Rounding only works with `f`. `%.2d` does not round.
- HTML squashes runs of spaces into one, so padding is invisible on a normal page. Inside `<pre>`, spaces and new lines are kept.
- PHP has many functions for working with strings:

  | Function | Example | Gives |
  |---|---|---|
  | `strlen()` | `strlen('Hello')` | `5` |
  | `trim()` | `trim('  Hi  ')` | `'Hi'` |
  | `ltrim()`, `rtrim()` | `ltrim('  Hi  ')` | `'Hi  '` |
  | `strtolower()` | `strtolower('Hello')` | `'hello'` |
  | `strtoupper()` | `strtoupper('Hello')` | `'HELLO'` |
  | `ucfirst()` | `ucfirst('hello there')` | `'Hello there'` |
  | `ucwords()` | `ucwords('hello there')` | `'Hello There'` |
  | `strcmp()` | `strcmp('Alpha', 'Beta')` | `-1`: the first string sorts first |

  `strcmp()` gives `0` when the two strings are the same, and `1` when the first sorts after the second.

  `strtoupper()` and the other case functions only change the letters A to Z. Letters such as `è` or `ħ` are left alone.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/04-formatting-output
php -S localhost:8000
```

## Steps

1. Follow along: in `output.php`, compare `print()` and `echo`:

   ```php
   print('<p>I was printed.</p>');
   echo '<p>Show this', ' and then this', ' and then that.</p>';
   ```

   Visit <http://localhost:8000/output.php>.

2. Follow along: use `printf()` to fill in a sentence, then to round a price and pad some numbers:

   ```php
   $ferry = 'Gaudos';
   printf('<p>The %s ferry leaves Ċirkewwa at %s.</p>', $ferry, '10:45');

   $cartTotal = 23.4567;
   printf('<p>Total in your cart: €%.2f</p>', $cartTotal);

   $student1 = 4;
   $student2 = 32;
   $student3 = 168;
   printf('<p>Students: %03d, %03d, %03d</p>', $student1, $student2, $student3);
   ```

   *Check: `€23.46`, rounded up, and `004, 032, 168`.*

3. Follow along: try padding, first on its own, then inside `<pre>`:

   ```php
   printf('<p>[%10s]</p>', 'Hello');
   echo '<pre>';
   printf("[%10s]\n", 'Hello');
   printf("[%-10s]\n", 'Hello');
   printf("[%'*10s]\n", 'Hello');
   printf("[%.2s]\n", 'Hello');
   printf("[%+d] [%+d]\n", 1024, -1024);
   echo '</pre>';
   ```

   *Check: the first line shows no padding. Inside `<pre>`, every line shows it.*

   These format strings are in double quotes, because `\n`, a new line, only works in double quotes. In single quotes, PHP prints a backslash and an `n`.

4. Follow along: try the string functions. The brackets show where the spaces are:

   ```php
   $messy = '   hello from gozo   ';
   echo '<pre>';
   echo '[' . $messy . ']' . "\n";
   echo '[' . trim($messy) . ']' . "\n";
   echo strlen($messy) . ' characters, ' . strlen(trim($messy)) . " after trim\n";
   echo strtoupper(trim($messy)) . "\n";
   echo ucfirst(trim($messy)) . "\n";
   echo ucwords(trim($messy)) . "\n";
   echo strcmp('Alpha', 'Beta') . ' ' . strcmp('Beta', 'Alpha') . ' ' . strcmp('Alpha', 'Alpha') . "\n";
   echo '</pre>';
   ```

   You can call a function on the result of another function: `strtoupper(trim($messy))` trims first, then changes the case.

5. Your turn: `receipt.php` has a shop, a customer, an order number and three prices before VAT. Below them, print this receipt inside `<pre>`, using `printf()`:

   ```
   THE CORNER CAFE
   Order 00042 for Maria Borg

   Espresso              1.89
   Croissant             3.30
   Ftira                 5.31
   --------------------------
   Total                10.50
   ```

   - The shop's name is in capitals, and the customer's name has no extra spaces and a capital at the start of each word. Use string functions. Do not edit the variables.
   - The order number is padded with zeros to 5 digits.
   - Each price includes VAT, worked out as in checkpoint 03.
   - Each item name is padded on the right to 20 characters, and each price is padded on the left to 6, with 2 decimal places.
   - The total is the sum of the three prices with VAT.
   - Hint: `printf("%'-26s\n", '')` prints a line of 26 dashes.

   Visit <http://localhost:8000/receipt.php>. *Check: the prices line up on the right, and none of them has more than 2 decimal places.*

## Done when

- [ ] `output.php` shows padded and unpadded output, and the string function results.
- [ ] `receipt.php` matches the receipt above, without changing the provided variables.
- [ ] You can say why the padding in step 3 only showed inside `<pre>`.
- [ ] You can say what `%8.2f` means, part by part.

## Previous / Next

Previous: [03 Constants and functions](../03-constants-functions/README.md). Next: [05 Inspecting variables](../05-inspecting-variables/README.md).
