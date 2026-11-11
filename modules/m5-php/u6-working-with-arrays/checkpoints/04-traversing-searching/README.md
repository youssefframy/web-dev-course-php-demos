# Checkpoint 04: Traversing and searching

## Goal

Loop over an array in several ways, change its items from inside a loop, and search an array for a value.

## Concepts

- Traversing an array means visiting every item. `foreach` is the usual way.
- `foreach` works on a copy of the array. Changing the loop variable does not change the original.
- A `&` in front of the loop variable makes it refer to the real item. A change then reaches the original.
- After a loop by reference, the variable still refers to the last item. Run `unset()` on it, or a later loop that uses the same name overwrites that item.
- A `for` loop with a counter works directly on the array, but only suits arrays with keys 0, 1, 2, and so on.
- `ArrayObject` wraps an array in an object, and `getIterator()` gives an iterator you can use in a `foreach`. It becomes useful with objects, later in the module. An iterator cannot be looped by reference.
- `array_walk($array, $callback)` calls a function for every item, with the value and the key.
- `in_array($value, $array)` tells you whether a value is in an array.
- `array_search($value, $array)` returns the key of the value, or `false`. The first item has the key 0, which is also falsy. So always compare with `!== false`.

## Before you start

Create `04-traversing.php` in the unit's [`arrays/`](../../arrays) folder, starting with:

```php
<?php
echo '<pre>';

$planetArray = [
    'Mercury' => 'Rocky', 'Venus' => 'Rocky', 'Earth' => 'Rocky', 'Mars' => 'Rocky',
    'Jupiter' => 'Gas', 'Saturn' => 'Gas', 'Uranus' => 'Ice', 'Neptune' => 'Gas'
];
```

## Steps

1. Follow along: loop by value, and try to change an item.

   ```php
   foreach ($planetArray as $name => $type) {
       if ($name === 'Uranus') {
           $type = 'Ice Giant';
       }
       printf("%s is a %s planet.\n", $name, $type);
   }
   echo $planetArray['Uranus'], "\n";
   ```

   *Check: the loop prints `Ice Giant`, but the last line prints `Ice`.* The loop changed its copy.

2. Follow along: loop by reference.

   ```php
   foreach ($planetArray as $name => &$type) {
       if ($name === 'Uranus') {
           $type = 'Ice Giant';
       }
   }
   unset($type);
   echo $planetArray['Uranus'], "\n";
   ```

   *Check: `Ice Giant`.* Now remove the `unset($type);` line and add this loop after it:

   ```php
   foreach ($planetArray as $name => $type) {
   }
   print_r($planetArray);
   ```

   *Check: Neptune now says `Ice Giant`, not `Gas`.* The loop variable was still tied to the last item, so each pass of the new loop wrote its value into Neptune. The last pass copied in Uranus's value. Put the `unset()` back, and remove the extra loop.

3. Follow along: a `for` loop.

   ```php
   $jupiterMoons = ['Europa', 'Io', 'Ganymede', 'Callisto'];
   for ($i = 0; $i < count($jupiterMoons); $i++) {
       $jupiterMoons[$i] .= ' Moon';
   }
   print_r($jupiterMoons);
   ```

   *Check: each name ends with `Moon`.*

4. Follow along: an iterator, and `array_walk()`.

   ```php
   $it = (new ArrayObject($planetArray))->getIterator();
   foreach ($it as $name => $type) {
       printf("%s is a %s planet.\n", $name, $type);
   }

   array_walk($planetArray, fn($value, $key) => printf("The next planet is %s (%s)\n", $key, $value));
   ```

   *Check: the same list printed twice, in two forms.*

5. Follow along: search.

   ```php
   $moons = ['Europa', 'Io', 'Ganymede', 'Callisto'];
   var_dump(in_array('Callisto', $moons));

   $result = array_search('Europa', $moons);
   if ($result !== false) {
       echo "Europa was found at key $result.\n";
   } else {
       echo "Europa was not found.\n";
   }
   ```

   *Check: `bool(true)` and `Europa was found at key 0.`* Change `!== false` to a plain `if ($result)` and run it again. It now says Europa was not found. Put it back.

6. Your turn: use `foreach` by reference to add the word `Moon` to every item in `$moons`, as you did with `for`. Remember the `unset()`.

## Done when

- [ ] You can say why changing the loop variable in a plain `foreach` leaves the array as it was.
- [ ] You can say why `unset()` follows a loop by reference.
- [ ] You can say why `array_search()` is compared with `!== false`.

## Previous / Next

Previous: [03 Array functions](../03-array-functions/README.md). Next: [05 Sorting](../05-sorting/README.md).
