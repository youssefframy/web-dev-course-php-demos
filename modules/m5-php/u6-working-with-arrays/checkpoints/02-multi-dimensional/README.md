# Checkpoint 02: Multi-dimensional arrays

## Goal

Store arrays inside arrays, and read a value from deep inside with a series of keys.

## Concepts

- The value of an array item can itself be an array. An array of arrays is a two-dimensional array.
- Two-dimensional arrays are the usual way to hold a table. The outer array holds the rows, and each row is an array. Data that comes from a database arrives this way, as you will see later in the module.
- To read a value, give one key for each level: `$gradeBook['Georgia Graham'][1]`.
- There is no limit to the depth. Three levels is common.
- Later in the module you will see another way to hold a row of data, as an object.

## Before you start

Create a file `02-multi.php` in the unit's [`arrays/`](../../arrays) folder, starting with:

```php
<?php
echo '<pre>';
```

Start the server in that folder if it is not running:

```sh
cd modules/m5-php/u6-working-with-arrays/arrays
php -S localhost:8000
```

## Steps

1. Follow along: a grade book with a list of subjects for each student.

   ```php
   $gradeBook = [
       'John Jefferson' => ['English', 'Geography', 'Physics'],
       'Georgia Graham' => ['English', 'French', 'Chemistry'],
       'Harriet Hasan' => ['Biology', 'Philosophy']
   ];
   print_r($gradeBook);
   printf("Georgia's second subject is %s\n", $gradeBook['Georgia Graham'][1]);
   ```

   *Check: `Georgia's second subject is French`.* The first key picks the student and the second picks the subject.

2. Follow along: a third level, with a midterm and a final mark for every subject.

   ```php
   $gradeBookDetail = [
       'John Jefferson' => [
           'English' => ['Midterm' => 90, 'Final' => 85],
           'Geography' => ['Midterm' => 62, 'Final' => 83],
           'Physics' => ['Midterm' => 73, 'Final' => 65]
       ],
       'Georgia Graham' => [
           'English' => ['Midterm' => 92, 'Final' => 98],
           'Geography' => ['Midterm' => 91, 'Final' => 100],
           'Physics' => ['Midterm' => 76, 'Final' => 78]
       ],
       'Harriet Hasan' => [
           'Biology' => ['Midterm' => 89, 'Final' => 81],
           'Philosophy' => ['Midterm' => 76, 'Final' => 82]
       ]
   ];
   printf("Harriet scored %d in her Philosophy final.\n", $gradeBookDetail['Harriet Hasan']['Philosophy']['Final']);
   ```

   *Check: `Harriet scored 82 in her Philosophy final.`*

3. Your turn: using `$gradeBookDetail`:
   - Print John's Physics midterm.
   - Print how many subjects John takes. `count()` works on any array, including one inside another.

   *Check: 73, and 3.*

4. Your turn: what happens if you ask for a key that is not there, such as `$gradeBookDetail['Harriet Hasan']['Physics']['Final']`? Try it and read the message. PHP gives a warning for the missing key, and the result is `null`. You will meet `isset()` and `??` as the way to avoid the warning when a key may be missing.

## Done when

- [ ] You read a value from a two-dimensional and from a three-dimensional array.
- [ ] You can say how many keys you need to read a value from a three-dimensional array.
- [ ] You know what PHP does when you ask for a key that does not exist.

## Previous / Next

Previous: [01 Creating arrays](../01-creating-arrays/README.md). Next: [03 Array functions](../03-array-functions/README.md).
