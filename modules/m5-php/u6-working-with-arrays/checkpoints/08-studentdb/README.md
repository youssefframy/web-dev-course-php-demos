# Checkpoint 08: StudentDB

## Goal

Build StudentDB, a student list that you can add to, delete from and sort, and that is kept in the session and in cookies.

## Concepts

- Nothing new in PHP: this checkpoint puts together arrays from this unit with sessions, cookies, filtering and escaping from Unit 5.
- The list is an array of arrays. Each student is an associative array with `name`, `surname` and `grade`. The key of each student, the id, is the number PHP gave it when it was added.
- Two small functions do all the remembering. `saveValue()` writes a value to the session and to a cookie. `loadValue()` reads it from the session, and if it is not there, from the cookie, which it then copies into the session. This is what lets the list survive a browser restart.
- A cookie holds text only, so the list is stored as JSON. `json_encode()` turns the array into text, and `json_decode($text, true)` turns it back into an array.
- A cookie holds about 4KB. That is room for a few dozen students, and no more. The browser quietly drops a cookie that is too big, so a long list would not survive a restart.
- Every link and the form send an `action`: `add`, `delete` or `sort`. A `switch` calls the matching function.
- `uasort()` and `<=>` do the sorting, as in checkpoint 05. For the id, `ksort()` sorts by key.
- The app has no database, so a delete is a link that sends `GET`. Following the same link twice does no harm, because removing a student that is already gone does nothing.
- As in Unit 5 checkpoint 09, the PHP lives in its own file, `includes/students.inc.php`, and is required on the first line of `index.php`. `session_start()` and `setcookie()` must run before the page sends any HTML.

## Before you start

The starter app is in the unit's [`studentdb/`](../../studentdb) folder. It has the page and the form, and nothing else. Start the server there:

```sh
cd modules/m5-php/u6-working-with-arrays/studentdb
php -S localhost:8000
```

Visit <http://localhost:8000>. *Check: a teal page with a StudentDB form. Clicking Add does nothing yet.*

## Steps

1. Follow along: create `includes/students.inc.php` with the session, and the two functions for saving and loading.

   ```php
   <?php
   session_start();

   const SORT_COLUMNS = ['id', 'name', 'surname', 'grade'];

   /**
    * Load a value from the session or, failing that, from a cookie.
    * @param string $key the key of the value to load
    * @param bool $json whether the cookie holds JSON that must be decoded
    * @return mixed the requested value, or null if not found
    */
   function loadValue(string $key, bool $json = false): mixed
   {
       $result = null;
       if (isset($_SESSION[$key])) {
           $result = $_SESSION[$key];
       } elseif (isset($_COOKIE[$key])) {
           $result = $json ? json_decode($_COOKIE[$key], true) : $_COOKIE[$key];
           $_SESSION[$key] = $result;
       }
       return $result;
   }

   /**
    * Save a value in the session and in a cookie.
    * @param string $key key to give the value
    * @param mixed $value the value to save
    * @param bool $json whether to JSON encode the value for the cookie
    * @param int $exp seconds until the cookie expires (default: about one month)
    * @return void
    */
   function saveValue(string $key, mixed $value, bool $json = false, int $exp = 2628288): void
   {
       $_SESSION[$key] = $value;
       setcookie($key, $json ? json_encode($value) : $value, time() + $exp, '/');
   }
   ```

   Then make it the very first line of `index.php`, above `<!doctype html>`:

   ```php
   <?php require 'includes/students.inc.php'; ?>
   ```

   `SORT_COLUMNS` lists the columns a visitor may sort by. You use it in step 5.

2. Follow along: add and delete. Add these functions to the end of `students.inc.php`:

   ```php
   /**
    * Add the student sent by the form to the list.
    * @return void
    */
   function addStudent(): void
   {
       global $studentList, $errors;
       $name = trim((string) filter_input(INPUT_POST, 'student_name'));
       $surname = trim((string) filter_input(INPUT_POST, 'student_surname'));
       $grade = filter_input(INPUT_POST, 'student_grade', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);

       if ($name === '' || $surname === '') {
           $errors[] = 'Please give a name and a surname.';
       }
       if ($grade === false || $grade === null) {
           $errors[] = 'The grade must be a whole number from 0 to 100.';
       }
       if (count($errors) > 0) {
           return;
       }

       $studentList[] = ['name' => $name, 'surname' => $surname, 'grade' => $grade];
       saveValue('studentList', $studentList, true);
   }

   /**
    * Delete the student whose id is in the address.
    * @return void
    */
   function deleteStudent(): void
   {
       global $studentList;
       $id = filter_input(INPUT_GET, 'studId', FILTER_VALIDATE_INT);
       if ($id !== false && $id !== null) {
           unset($studentList[$id]); // Does nothing if the id is not there
           saveValue('studentList', $studentList, true);
       }
   }
   ```

   The slides read the grade as plain text and `%d` prints anything else as 0. The filter from Unit 5 checkpoint 07 refuses a grade that is not a whole number from 0 to 100, and the form says why. `global` lets a function work on a variable from outside it, as in Unit 4 checkpoint 03.

3. Follow along: read the saved values and choose the action. Add this at the end of `students.inc.php`, below the functions:

   ```php
   // Initialise values
   $errors = [];
   $studentList = loadValue('studentList', true) ?? [];
   $sortBy = 'id';

   // Determine action
   $action = null;
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $action = filter_input(INPUT_POST, 'action');
   } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
       $action = filter_input(INPUT_GET, 'action');
   }

   switch ($action) {
       case 'add':
           addStudent();
           break;
       case 'delete':
           deleteStudent();
           break;
   }
   ```

   `loadValue()` gives `null` when nothing is saved, and `?? []` turns that into an empty list.

4. Follow along: show the errors and the students. In `includes/main.inc.php`, add these two blocks inside the outer `<div class="grid ...">`, after the form card:

   ```php
   <?php if (count($errors) > 0) { ?>
       <div class="p-2 g-col-12">
           <div class="alert alert-danger mb-0">
               <?php
               foreach ($errors as $error) {
                   echo '<p class="mb-0">' . htmlspecialchars($error) . '</p>';
               }
               ?>
           </div>
       </div>
   <?php } ?>
   <?php if (count($studentList) > 0) { ?>
       <div class="p-2 g-col-12">
           <div class="card">
               <div class="card-body">
                   <table class="table table-striped">
                       <thead>
                           <tr>
                               <th><a href="index.php?action=sort&sortBy=id">ID</a></th>
                               <th><a href="index.php?action=sort&sortBy=name">Name</a></th>
                               <th><a href="index.php?action=sort&sortBy=surname">Surname</a></th>
                               <th><a href="index.php?action=sort&sortBy=grade">Grade</a></th>
                               <th>Action</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php
                           foreach ($studentList as $key => $student) {
                               printf(
                                   '<tr><td>%3d</td><td>%s</td><td>%s</td><td>%d</td><td>%s</td></tr>',
                                   $key,
                                   htmlspecialchars($student['name']),
                                   htmlspecialchars($student['surname']),
                                   $student['grade'],
                                   "<a href='index.php?action=delete&studId=$key'><i class='bi bi-trash3-fill'></i></a>"
                               );
                           }
                           ?>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   <?php } ?>
   ```

   The table only appears when there is a student. The names are escaped with `htmlspecialchars()`, because they came from a visitor. The slides print them as they are.

5. Follow along: try it. Add three students, then delete one.

   *Check: the students appear with ids 0, 1 and 2. After deleting id 1, the ids are 0 and 2. A new student gets id 3, not 1.* Try the grade 101, and a name made only of spaces. *Check: a red message, and nothing added.*

   Refresh with Ctrl+R, not the form. *Check: the list is still there.* Close the browser, open it again, and visit the page. *Check: the list came back from the cookie.* Clicking the column headers does nothing yet.

6. Follow along: sorting. Add these two functions above the `// Initialise values` comment:

   ```php
   /**
    * Remember which column the list is sorted by.
    * @return void
    */
   function setSort(): void
   {
       global $sortBy;
       $requested = filter_input(INPUT_GET, 'sortBy');
       if (in_array($requested, SORT_COLUMNS, true)) {
           $sortBy = $requested;
           saveValue('sortBy', $sortBy);
       }
   }

   /**
    * Sort the list by the saved column.
    * @return void
    */
   function sortStudents(): void
   {
       global $studentList, $sortBy;
       if ($sortBy === 'id') {
           ksort($studentList);
       } else {
           uasort($studentList, fn($a, $b) => $a[$sortBy] <=> $b[$sortBy]);
       }
   }
   ```

   Replace the line `$sortBy = 'id';` in the initialise block with:

   ```php
   $sortBy = loadValue('sortBy');
   if (!in_array($sortBy, SORT_COLUMNS, true)) {
       $sortBy = 'id';
   }
   ```

   Add a case to the `switch`:

   ```php
       case 'sort':
           setSort();
           break;
   ```

   And call the sort once, after the `switch`:

   ```php
   sortStudents();
   ```

   The sort column is saved, so the order stays after a refresh. `in_array()` with `true` checks the requested column against the list, so a made-up `sortBy` cannot reach `$a[$sortBy]` and cause warnings. The slides sort inside each action. Sorting once, after every request, means the list is in order whatever the visitor just did.

   Try it. *Check: the name, surname and grade headers sort the list, ID puts it back in id order, and the order stays after a refresh.* Visit `index.php?action=sort&sortBy=bogus` and check the list is unchanged and there are no warnings.

7. Your turn: under the table, print how many students there are and their average grade, to one decimal place. `array_column($studentList, 'grade')` returns just the grades, as a flat array. Use it with `array_sum()` and `count()`.

   *Check: with grades 70 and 95, the line says `Students: 2. Average grade: 82.5`.*

## Done when

- [ ] Students can be added and deleted, and bad input is refused with a message.
- [ ] The list survives a refresh and a browser restart.
- [ ] All four headers sort the list, and the order is kept after a refresh.
- [ ] There are no `headers already sent` warnings, and a made-up `sortBy` causes none.
- [ ] You can say why the list is saved as JSON in the cookie.

## Previous / Next

Previous: [07 Sets, queues and stacks](../07-sets-queues-stacks/README.md). Next: [09 Self-study](../09-self-study/README.md).
