# Checkpoint 01: Creating arrays

## Goal

Create indexed and associative arrays, read values from them, and use `range()`, `count()` and `array_pad()`.

## Concepts

- A PHP array is an ordered map: a list of keys, each with a value. In many other languages an array is a fixed-size list of one type. In PHP the keys can be numbers or strings, the values can be of any type and mixed, and the array grows and shrinks as you need.
- An indexed array has numeric keys. If you give only values, PHP numbers them from 0.
- An associative array has string keys. You can mix numeric and string keys in one array.
- `[ ]` and `array( )` build the same array. This course uses `[ ]`.
- When you add a value without a key, PHP uses one more than the largest numeric key so far.
- A key of `1` and a key of `'1'` are the same key. The second assignment overwrites the first.
- `range(30, 39)` and `range('A', 'E')` build an indexed array of a run of numbers or letters.
- `count()` gives the number of items.
- `array_pad($array, $size, $value)` returns a new array padded to `$size` items with `$value`. A negative size pads at the front. It does not change the array you give it.

## Before you start

Work in the unit's [`arrays/`](../../arrays) folder. Create a file `01-creating.php` and start the server there:

```sh
cd modules/m5-php/u6-working-with-arrays/arrays
php -S localhost:8000
```

Visit <http://localhost:8000/01-creating.php>. Every step adds to the same file, and `print_r()` output is easier to read in a `<pre>`, so start the file with:

```php
<?php
echo '<pre>';
```

## Steps

1. Follow along: make an indexed array, in both syntaxes, and compare them.

   ```php
   $cities = ['London', 'Paris', 'New York', 'Valletta', 'Bangkok'];
   print_r($cities);

   $longCities = array('London', 'Paris', 'New York', 'Valletta', 'Bangkok');
   var_dump($cities === $longCities);
   ```

   *Check: keys 0 to 4, then `bool(true)`.*

2. Follow along: make an associative array.

   ```php
   $ninjaCoders = ['CEO' => 'Alice Anderson', 'CTO' => 'Bob Barker', 'SVP Sales' => 'Craig Coleman'];
   print_r($ninjaCoders);
   ```

   Now give one value a number key and add another with no key:

   ```php
   $ninjaCoders = ['CEO' => 'Alice Anderson', 'CTO' => 'Bob Barker', 'SVP Sales' => 'Craig Coleman', 20 => 'Desiree Diamont', 'Eliott Earhart'];
   print_r($ninjaCoders);
   ```

   *Check: Eliott has the key 21.*

3. Follow along: read values.

   ```php
   printf("The second city is %s\n", $cities[1]);
   printf("The CTO of the company is %s\n", $ninjaCoders['CTO']);
   $level = 'SVP';
   printf("The employee is %s.\n", $ninjaCoders["$level Sales"]);
   ```

   Number keys are not quoted, string keys are. A key can contain spaces.

   *Check: `Paris`, `Bob Barker`, `Craig Coleman`.*

4. Follow along: show that `1` and `'1'` are one key.

   ```php
   $same = [1 => 'one'];
   $same['1'] = 'uno';
   print_r($same);
   ```

   *Check: one item, with the value `uno`.*

5. Follow along: build an array by assigning to keys, and fill one with `range()`.

   ```php
   $sports[] = 'Football';
   $sports['USA'] = 'Soccer';
   $sports[2] = 'Basketball';
   print_r($sports);

   $thirties = range(30, 39);
   $fiveLetters = range('A', 'E');
   var_dump($thirties, $fiveLetters);
   ```

   *Check: `$sports` has the keys 0, `USA` and 2. Both range arrays are indexed.* Why did Basketball get the key 2, not 1?

6. Follow along: count and pad.

   ```php
   printf("The size of the \$thirties array is %d\n", count($thirties));
   $padded = array_pad($sports, 6, 'Some other sport');
   print_r($padded);
   $paddedFront = array_pad($sports, -6, 'Some other sport');
   print_r($paddedFront);
   printf("\$sports still has %d items\n", count($sports));
   ```

   *Check: 10 items. `$padded` has the new items at the end, `$paddedFront` at the front, and `$sports` is still 3 items.*

7. Your turn: make an associative array called `$capitals` with four countries and their capitals. Print one capital with its country as the key. Then add a fifth country, and print the number of items.

   *Check: you read a value with a quoted key, and `count()` gives 5.*

## Done when

- [ ] You created an indexed and an associative array, and read values from both.
- [ ] You can say what key PHP gives a value added with no key.
- [ ] You can say why `array_pad()` needs its result stored in a variable.

## Previous / Next

Previous: [Unit 5, 10 Self-study](../../../u5-processing-forms/checkpoints/10-self-study/README.md). Next: [02 Multi-dimensional arrays](../02-multi-dimensional/README.md).
