# Checkpoint 03: Return values and scope

## Goal

Control what a function gives back, and see which variables a function can and cannot reach.

## Concepts

- `return` normally sends back a copy of a value. Changing that copy does not change where it came from.
- With `&` before the function's name, the function returns a reference instead: the variable itself, not a copy. To keep the reference, the caller must also use `&` when storing it: `$car = &getVehicleRef(1);`. Without that second `&`, PHP copies the value anyway.
- Every variable has a scope: the part of the code where it exists. A variable created outside any function is global. A variable created inside a function is local to that function.
- A function cannot see global variables unless it asks for them. `global $userName;` inside the function brings the global variable in.
- The code outside a function cannot see the function's local variables at all. They are created when the function is called and thrown away when it returns. To get a value out, return it.
- A function can be declared inside another function. The inner function does not exist until the outer one has run. After that, it is an ordinary function that any code can call. Calling the outer function a second time is an error, because PHP would declare the inner function twice. Because of this, nested function declarations are rare in real code. Checkpoint 04 shows a better way to keep a helper inside a function.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u4-functions/checkpoints/03-scope
php -S localhost:8000
```

## Steps

1. Follow along: in `scope.php`, under the comment, return a value from an array:

   ```php
   echo '<h5>Returning by Value</h5>';
   $garage = ['Kia', 'BMW', 'Tesla', 'Ford', 'GM'];

   function getVehicle($carNumber = 0)
   {
       global $garage;
       return $garage[$carNumber];
   }

   echo '<p>Array before changes:</p>';
   print_r($garage);
   $car = getVehicle(1);
   $car = 'Mercedes';
   echo '<p>Array after changes:</p>';
   print_r($garage);
   ```

   Visit <http://localhost:8000/scope.php>. *Check: both arrays still have `BMW` at `[1]`.* `getVehicle(1)` returned a copy of `'BMW'`, and only the copy became `'Mercedes'`. Ignore `global` for now. Step 4 explains it.

2. Follow along: return a reference. A function name can only be declared once, so this one has its own name:

   ```php
   echo '<h5>Returning by Reference</h5>';
   function &getVehicleRef($carNumber = 0)
   {
       global $garage;
       return $garage[$carNumber];
   }

   $car = &getVehicleRef(1);
   $car = 'Mercedes';
   echo '<p>Array after changes:</p>';
   print_r($garage);
   ```

   *Check: `[1] => Mercedes`.* `$car` and `$garage[1]` are now the same variable.

3. Follow along: in the line `$car = &getVehicleRef(1);`, delete the `&` before `getVehicleRef`. Refresh.

   *Check: the last array says `BMW` again.* The function still returns a reference, but without `&` in the call, PHP stored a copy in `$car`. Put the `&` back.

4. Follow along: try to read global variables from inside a function:

   ```php
   echo '<h5>Global Scope</h5>';
   $userName = 'Alice';
   $userSurname = 'Anderson';

   function getFullName()
   {
       return $userName . ' ' . $userSurname;
   }

   echo '<p>Full Name: ' . getFullName() . '</p>';
   ```

   *Check: two warnings, `Undefined variable $userName` and `$userSurname`, and an empty name.* Inside `getFullName()`, PHP only looks in the function's own scope. Now add this as the first line of the function:

   ```php
   global $userName, $userSurname;
   ```

   *Check: `Full Name: Alice Anderson`, with no warnings.* That is why `getVehicle()` needed `global $garage;`.

5. Follow along: the opposite direction. Create variables inside a function and try to use them outside:

   ```php
   echo '<h5>Local Scope</h5>';
   function setCarDetails()
   {
       $carMake = 'Seat';
       $carModel = 'Arona';
       $carYear = 2018;
   }

   setCarDetails();
   printf('<p>It is a %d %s %s.</p>', $carYear, $carMake, $carModel);
   ```

   *Check: three `Undefined variable` warnings, and `It is a 0 .`* The three variables were local to `setCarDetails()`, and were gone once it returned.

   Fix it by returning what the caller needs. Replace the whole section with:

   ```php
   echo '<h5>Local Scope</h5>';
   function getCarDetails()
   {
       $carMake = 'Seat';
       $carModel = 'Arona';
       $carYear = 2018;
       return sprintf('%d %s %s', $carYear, $carMake, $carModel);
   }

   echo '<p>It is a ' . getCarDetails() . '.</p>';
   ```

   *Check: `It is a 2018 Seat Arona.`*

6. Follow along: declare a function inside another:

   ```php
   echo '<h5>Nested Functions</h5>';
   function showPrices(...$prices)
   {
       function showAsEuro($value)
       {
           return '€' . number_format($value, 2, '.', ',');
       }

       echo '<ul>';
       foreach ($prices as $price) {
           echo '<li>' . showAsEuro($price) . '</li>';
       }
       echo '</ul>';
   }

   showPrices(34.789, 212.009, 1612.881, 12.89);
   ```

   *Check: a list with `€34.79`, `€212.01`, `€1,612.88` and `€12.89`.* Add one more line, `showPrices(5);`, at the end and refresh. *Check: a fatal error, `Cannot redeclare function showAsEuro()`.* Delete that line again.

7. Your turn: create `counter.php` in this folder.
   - Set a global `$visits = 0;`. Write `countVisit()` that adds one to `$visits` using `global`. Call it three times and show `Visits: 3`.
   - Write `countVisitBy(&$counter)` that does the same through a reference parameter instead of `global`. Set `$clicks = 0;`, call it twice with `$clicks`, and show `Clicks: 2`.
   - Write `getGreeting($name)` that builds the message `Welcome back, $name!` in a local variable and returns it. Show it for `Bob`. Then try to `echo` the local variable outside the function, read the warning, and remove that line.

   Visit <http://localhost:8000/counter.php>. *Check: `Visits: 3`, `Clicks: 2`, and `Welcome back, Bob!`, with no warnings.*

## Done when

- [ ] `scope.php` shows every section from steps 1 to 6, with no warnings or errors.
- [ ] `counter.php` shows the three results from step 7.
- [ ] You can say why `$car = getVehicleRef(1);` without `&` does not change the array.
- [ ] You can say which is clearer, `global` or a reference parameter, and why.

## Previous / Next

Previous: [02 Writing functions](../02-functions/README.md). Next: [04 Functions as values](../04-functional/README.md).
