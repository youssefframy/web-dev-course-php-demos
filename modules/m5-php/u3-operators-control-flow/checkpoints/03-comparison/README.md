# Checkpoint 03: Comparison and conditional operators

## Goal

Compare two values and get `true` or `false` back, and pick a value with `?:` and `??`. These are the building blocks of every `if` you will write.

## Concepts

When the two sides have different types, PHP converts one of them before comparing. This is called type juggling. Comparing an int with a float, for example, turns the int into a float.

| Operator | Name | `true` when |
|---|---|---|
| `$a == $b` | Equal | the values match after type juggling |
| `$a === $b` | Identical | the values match and so do the types |
| `$a != $b` or `$a <> $b` | Not equal | the values differ after type juggling |
| `$a !== $b` | Not identical | the values or the types differ |
| `$a < $b`, `$a > $b` | Less than, greater than | as in maths |
| `$a <= $b`, `$a >= $b` | Less than or equal, greater than or equal | as in maths |
| `$a <=> $b` | Spaceship | Not true or false: gives `0` if equal, less than 0 if `$a` is smaller, more than 0 if `$a` is bigger |

Prefer `===` to `==`. It never juggles types, so it never surprises you.

Logical operators combine true-or-false values. You will use them in checkpoint 05.

| Operator | `true` when |
|---|---|
| `$a && $b`, `$a and $b` | both are true |
| `$a \|\| $b`, `$a or $b` | either is true |
| `$a xor $b` | exactly one is true |
| `!$a` | `$a` is not true |

`and`, `or` and `xor` are weaker than `=`. `$x = true xor true;` stores `true` in `$x` first and only then does the `xor`. Put brackets round them when you store the result: `$x = (true xor true);`.

Some operators mean something different for arrays:

| Operator | Name | Gives |
|---|---|---|
| `$a + $b` | Union | `$a`, plus any keys from `$b` that `$a` does not have |
| `$a == $b` | Equality | `true` if both have the same key and value pairs |
| `$a === $b` | Identity | `true` if they also are in the same order and of the same types |
| `$a != $b`, `$a !== $b` | Inequality, non-identity | the opposite of the two above |

Two operators choose a value:

- The ternary, `condition ? valueIfTrue : valueIfFalse`. `$x = $a == $b ? 'Same' : 'Different';`
- Null coalescing, `$a ?? $b`. Gives `$a` if it exists and is not `null`, otherwise `$b`. It does not warn you if `$a` does not exist.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u3-operators-control-flow/checkpoints/03-comparison
php -S localhost:8000
```

## Steps

1. Follow along: in `operators.php`, under the comment, compare an int with a string:

   ```php
   echo '<h5>Comparison Operators</h5>';
   $a = 42;
   $b = '42';
   echo '<pre>';
   var_dump($a == $b);
   var_dump($a === $b);
   var_dump($a != $b);
   var_dump($a !== $b);
   var_dump($a < 50, $a > 50, $a <= 42, $a >= 43);
   var_dump(1 <=> 2, 2 <=> 2, 3 <=> 2);
   echo '</pre>';
   ```

   Visit <http://localhost:8000/operators.php>. *Check: `==` is true but `===` is false, because one side is a string. The spaceship gives `-1`, `0` and `1`.*

2. Follow along: add the logical and array operators:

   ```php
   echo '<h5>Logical Operators</h5>';
   echo '<pre>';
   var_dump(true && false);
   var_dump(true || false);
   var_dump(true xor true);
   var_dump(!true);
   echo '</pre>';

   echo '<h5>Array Operators</h5>';
   $first = ['a' => 1, 'b' => 2];
   $second = ['b' => 3, 'c' => 4];
   $sameButShuffled = ['b' => 2, 'a' => 1];
   echo '<pre>';
   print_r($first + $second);
   var_dump($first == $sameButShuffled);
   var_dump($first === $sameButShuffled);
   echo '</pre>';
   ```

   *Check: the union has `b => 2`, not `3`, because `$first` already had a `b`. The shuffled array is equal, but not identical.*

3. Follow along: add the two conditional assignment operators:

   ```php
   echo '<h5>Conditional Assignment</h5>';
   $a = 5;
   $b = 5;
   $x = $a == $b ? 'Same' : 'Different';
   echo "<p>Ternary: $x</p>";

   $nickname = null;
   $x = $nickname ?? 'Guest';
   echo "<p>Null coalescing: $x</p>";
   $x = $undefinedVariable ?? 'No warning either';
   echo "<p>Null coalescing: $x</p>";
   ```

   *Check: `Same`, `Guest`, and no warning about `$undefinedVariable`.* Change `$b` to `6` and refresh. *Check: `Different`.*

4. Your turn: open `compare.php` and visit <http://localhost:8000/compare.php>. The table has one row. Add a row for each expression below, writing your guess in the "My guess" column before you refresh:

   | Expression |
   |---|
   | `42 === '42'` |
   | `42 != '42'` |
   | `42 !== '42'` |
   | `1.0 == 1` |
   | `1.0 === 1` |
   | `'apple' < 'banana'` |
   | `5 <=> 3` |
   | `3 <=> 5` |
   | `true && false` |
   | `true \|\| false` |
   | `true xor true` |
   | `!false` |
   | `['a', 'b'] == [1 => 'b', 0 => 'a']` |
   | `['a', 'b'] === [1 => 'b', 0 => 'a']` |
   | `null ?? 'empty'` |
   | `10 > 5 ? 'big' : 'small'` |

   Copy the existing row each time, and change the expression in `$result = ...;` and the first two cells. For `xor`, remember the brackets: `$result = (true xor true);`. In the first cell, write `<` and `>` as `&lt;` and `&gt;` so the browser shows them.

   *Check: every guess you got wrong, you can now explain.*

## Done when

- [ ] `operators.php` shows the comparison, logical, array and conditional sections with no warnings.
- [ ] `compare.php` has 17 rows, and you have compared every guess with PHP's answer.
- [ ] You can say why `42 == '42'` is true and `42 === '42'` is false.
- [ ] You can say what `??` gives you that `isset()` and a ternary would otherwise need.

## Previous / Next

Previous: [02 Assignment operators](../02-assignment/README.md). Next: [04 Selection](../04-selection/README.md).
