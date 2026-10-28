# Checkpoint 02: Data types

## Goal

See which type PHP gives each value, how PHP treats values as true or false, and how to convert a value from one type to another.

## Concepts

Every value has a type. PHP chooses it from the value itself.

| Type | Holds | Examples |
|---|---|---|
| `int` | Whole numbers | `42`, `-7`, `1_000_000`, `0x2A` (hex), `0b101010` (binary) |
| `float` | Numbers with a decimal point | `3.5`, `4.2e5` |
| `string` | Text | `'Mosta'`, `"Hi $name"`, a heredoc |
| `bool` | `true` or `false` | `true` |
| `array` | Many values, each under a key | `[23.1, 22.9]`, `['CEO' => 'Alice']` |
| `object` | Values and behaviour, built from a class | `new Ferry()` |
| `null` | No value at all | `null` |

The first four hold one value each, and are called scalar types. Arrays and objects hold several values. You will meet them properly in later units.

- `gettype($x)` returns the name of a type as a string. For historical reasons, it says `double` for a float.
- `echo` cannot show an array or an object. `print_r()` can.
- When PHP needs a true-or-false answer, these values count as false, and are called falsy: `false`, `0`, `0.0`, `''`, `'0'`, an empty array and `null`. Every other value is truthy.
- `echo false` prints nothing at all, and `echo true` prints `1`.
- Type coercion: PHP converts a value by itself when an operation needs a different type. `42 + "89"` turns `"89"` into a number and gives `131`.
- Type casting: you convert a value on purpose, by writing the type in brackets in front of it: `(int) "29"`. You can cast to `(int)`, `(float)`, `(string)`, `(bool)`, `(array)` and `(object)`. `intval()`, `floatval()`, `strval()` and `boolval()` do the same job for the first four.
- Casting a float to `(int)` drops everything after the decimal point. `(int) 42.82` is `42`, not `43`.
- Functions starting with `is_` test a type and return `true` or `false`: `is_int()`, `is_float()`, `is_string()`, `is_bool()`, `is_array()`, `is_null()` and others. `is_numeric()` is true for numbers, and for strings that hold nothing but a number, such as `"4.2"`. `is_scalar()` is true for the four scalar types.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u2-language-basics/checkpoints/02-data-types
php -S localhost:8000
```

## Steps

1. Follow along: in `types.php`, under the comment, show a value of each scalar type and its type:

   ```php
   $anInt = 42;
   echo "<p>The value $anInt is an " . gettype($anInt) . '</p>';
   $aFloat = 3.5;
   echo "<p>The value $aFloat is a " . gettype($aFloat) . '</p>';
   $town = 'Mosta';
   echo "<p>The value $town is a " . gettype($town) . '</p>';
   $isRaining = false;
   echo "<p>The value $isRaining is a " . gettype($isRaining) . '</p>';
   $isSunny = true;
   echo "<p>The value $isSunny is a " . gettype($isSunny) . '</p>';
   ```

   Visit <http://localhost:8000/types.php>. *Check: the float is reported as `double`, `false` shows no value at all, and `true` shows as `1`.*

2. Follow along: add an array of each kind, and an object:

   ```php
   $temperatures = [23.1, 22.9, 16.8, 14.3];
   echo '<p>Temperatures:</p>';
   print_r($temperatures);

   $company = ['CEO' => 'Alice Abela', 'CTO' => 'Bob Bonnici'];
   echo '<p>Company:</p>';
   print_r($company);

   class Ferry
   {
       public $name = '';
   }

   $boat = new Ferry();
   $boat->name = 'Gaudos';
   echo '<p>Ferry:</p>';
   print_r($boat);
   ```

   The first array is numbered from 0. The second uses names, called keys, instead of numbers.

3. Add `echo $company;` and refresh. *Check: a warning, "Array to string conversion", and the word `Array`. `echo` cannot show an array.* Remove the line.

4. Follow along: let PHP convert a type for you, then convert two values yourself:

   ```php
   $val1 = 42;
   $val2 = "89";
   $val3 = $val1 + $val2;
   echo "<p>Type coercion: $val3</p>";

   $val4 = "29";
   $val5 = 42.82;
   $val6 = (int) $val4 + (int) $val5;
   echo "<p>Type casting: $val6</p>";
   ```

   *Check: `131` and `71`. 42.82 became 42, so it was cut short, not rounded.*

5. Change `$val2` to `"89 apples"` and refresh. *Check: still `131`, but with a warning, "A non-numeric value encountered".* Now change it to `"apples"`. *Check: a fatal error. PHP refuses to add a number to text that has no number at the start.* Change it back to `"89"`.

6. Your turn: open `truthy.php` and visit <http://localhost:8000/truthy.php>. The table has one row, for `0`. Add a row for each of these values, writing your guess in the "My guess" column before you refresh:

   | Value |
   |---|
   | `0.0` |
   | `'0'` |
   | `''` |
   | `'0.0'` |
   | `'false'` |
   | `' '` (one space) |
   | `[]` |
   | `null` |
   | `'4.2'` |
   | `'4.2kg'` |

   Copy the existing row each time, and change `$value` and the first two cells. Do not change the last three cells.

   *Check: three of the strings are truthy, even though they look as if they mean nothing or false. Find them, and say why.*

## Done when

- [ ] `types.php` shows the five scalar values, the two arrays and the ferry, with no warnings.
- [ ] `truthy.php` has 11 rows, and you have compared every guess with PHP's answer.
- [ ] You can say why `(int) 42.82` is `42`.
- [ ] You can say why `echo $isRaining` showed nothing.

## Previous / Next

Previous: [01 Strings and comments](../01-strings/README.md). Next: [03 Constants and functions](../03-constants-functions/README.md).
