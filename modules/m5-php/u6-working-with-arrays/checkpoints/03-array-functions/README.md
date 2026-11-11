# Checkpoint 03: Array functions

## Goal

Use the functions that take an array apart, change it, and turn arrays and variables into each other.

## Concepts

- `list()`, or the short form `[ ]` on the left of `=`, unpacks an array into variables. With keys, you choose which item goes into which variable.
- `array_slice($array, $offset, $length)` returns a part of an array. The original is not changed.
- `array_chunk($array, $size, $preserveKeys)` returns an array of smaller arrays, each `$size` long. By default the keys are reset. Pass `true` to keep them.
- `array_keys()` and `array_values()` return the keys and the values, each as an indexed array.
- `array_key_exists($key, $array)` is true when the key is there, even if its value is `null`.
- `array_splice($array, $offset, $length, $replacement)` removes part of an array and can put something else in its place. Unlike `array_slice()`, it changes the array you give it.
- `extract($array)` creates one variable for each item, named after the key.
- `compact('a', 'b')` does the opposite. It takes variable names, as strings, and returns an array of their names and values.
- `extract()` can overwrite variables you already have. Never use it on data from a visitor, such as `$_POST`.

## Before you start

Create `03-functions.php` in the unit's [`arrays/`](../../arrays) folder, and start with the planets array. Every step uses it:

```php
<?php
echo '<pre>';

$planetArray = [
    'Mercury' => 'Rocky', 'Venus' => 'Rocky', 'Earth' => 'Rocky', 'Mars' => 'Rocky',
    'Jupiter' => 'Gas', 'Saturn' => 'Gas', 'Uranus' => 'Ice', 'Neptune' => 'Gas'
];
```

## Steps

1. Follow along: unpack two items by key.

   ```php
   ['Mercury' => $first, 'Venus' => $second] = $planetArray;
   echo "$first and $second\n";
   ```

   *Check: `Rocky and Rocky`.* The values are the same, so change the keys to `'Mercury'` and `'Jupiter'` to see two different values.

2. Follow along: slice and chunk.

   ```php
   print_r(array_slice($planetArray, 0, 4));
   print_r(array_chunk($planetArray, 3));
   print_r(array_chunk($planetArray, 3, true));
   ```

   *Check: the slice has the four rocky planets. The first chunk output has keys 0, 1, 2 and no planet names. The second has the planet names as keys.*

3. Follow along: keys, values and key checks.

   ```php
   print_r(array_keys($planetArray));
   print_r(array_values($planetArray));

   $body = 'Pluto';
   printf("%s is %s a planet.\n", $body, array_key_exists($body, $planetArray) ? '' : 'not');
   ```

   *Check: `Pluto is not a planet.`* Try `'Earth'.`

4. Follow along: replace the gas and ice planets with two newly found ones. Work on a copy, so the next steps still have the full list.

   ```php
   $copy = $planetArray;
   array_splice($copy, 4, count($copy), ['HD 260655 b', 'HD 260655 c']);
   print_r($copy);
   ```

   *Check: Mercury to Mars keep their names as keys, and the two new names follow with the keys 0 and 1.* `array_splice()` keeps string keys on the items it leaves alone, but the items it adds get number keys.

5. Follow along: turn keys into variables, and variables into keys.

   ```php
   extract($planetArray);
   echo "Mars is a $Mars planet.\n";

   $star1 = 'Sol';
   $star2 = 'Proxima Centauri';
   $star3 = 'Rigil Kentaurus';
   print_r(compact('star1', 'star2', 'star3'));
   ```

   *Check: `Mars is a Rocky planet.`, then an array with the keys `star1`, `star2` and `star3`.*

6. Your turn: use `array_slice()` to get the last three planets. A negative offset counts from the end. Then use `array_keys()` and `count()` to print `There are 8 planets.` without typing the 8.

## Done when

- [ ] You can say which of `array_slice()` and `array_splice()` changes its array.
- [ ] You can say how to keep the keys with `array_chunk()`.
- [ ] You can say why `extract($_POST)` is a bad idea.

## Previous / Next

Previous: [02 Multi-dimensional arrays](../02-multi-dimensional/README.md). Next: [04 Traversing and searching](../04-traversing-searching/README.md).
