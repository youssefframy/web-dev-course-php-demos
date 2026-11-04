# Checkpoint 04: Functions as values

## Goal

Store functions in variables, pass them to other functions, and write short functions without a name.

## Concepts

- A programming paradigm is a style of programming. In imperative programming you tell the computer, step by step, how to change its state. Procedural code groups those steps into functions, and object-oriented code groups them with the data they work on. In declarative programming you describe the result you want. Functional programming builds the result from calls to functions. PHP supports procedural, object-oriented and functional styles. JavaScript is moving the same way.
- A variable function is a variable that holds a function's name as a string. Adding `()` to the variable calls that function. `function_exists()` checks first that a function with that name exists.
- An anonymous function has no name. You store it in a variable, or pass it straight to another function. Because the whole thing is an expression, it ends with `;`. Anonymous functions are also called closures, or lambda functions.
- An arrow function is a short anonymous function: `fn($name) => "Hello, $name"`. It replaces `function`, the braces and `return`. It can only hold one expression.
- A callback is a function you pass to another function, for it to call. `array_map($callback, $array)` calls the callback once for every value in the array, and returns a new array of the results. The original array is not changed.
- A callback used once, in one place, is sometimes called a throw-away function.
- Arrow functions can read variables from the code around them. A function written with `function` cannot, unless you list them after `use`: `function ($price) use ($freeThreshold) { ... }`.
- Rule of thumb: one expression, use an arrow function. More than one line, use a closure.

## Before you start

Start the server in this checkpoint's folder:

```sh
cd modules/m5-php/u4-functions/checkpoints/04-functional
php -S localhost:8000
```

## Steps

1. Follow along: in `functional.php`, under the comment, call functions through variables:

   ```php
   echo '<h5>Variable Functions</h5>';
   function myFunc1()
   {
       echo '<p>Hello! This is myFunc1()</p>';
   }

   function myFunc2($arg)
   {
       echo "<p>Hello! This is myFunc2() with argument $arg</p>";
   }

   $myVar1 = 'myFunc1';
   $myVar1();
   $myVar2 = 'myFunc2';
   $myVar2(1234);

   $myVar3 = 'bogusFunction';
   if (function_exists($myVar3)) {
       $myVar3();
   } else {
       echo '<p>There is no function with that name!</p>';
   }
   ```

   Visit <http://localhost:8000/functional.php>. *Check: the two greetings, then `There is no function with that name!`* Without the `if`, `$myVar3()` would stop the page with a fatal error.

2. Follow along: an anonymous function, then the same idea as an arrow function:

   ```php
   echo '<h5>Anonymous Functions</h5>';
   $greeting = function ($name) {
       return "Hello, $name";
   };
   printf('<p>%s</p>', $greeting('Bob'));

   $otherGreeting = fn($name) => "Hello again, $name";
   printf('<p>%s</p>', $otherGreeting('Bob'));
   ```

   *Check: `Hello, Bob`, then `Hello again, Bob`.* Note the `;` after the closing brace of the first function.

3. Follow along: pass an arrow function to `array_map()`:

   ```php
   echo '<h5>Callbacks</h5>';
   $productPrices = [34.789, 212.009, 1612.881, 12.89];
   $result = array_map(fn($price) => '€' . number_format($price, 2, '.', ','), $productPrices);
   print_r($result);
   ```

   *Check: `€34.79`, `€212.01`, `€1,612.88` and `€12.89`.* The arrow function never got a name. It was made, used for each price, and thrown away.

4. Follow along: any cart over €40 gets free delivery. Try it with an arrow function first:

   ```php
   echo '<h5>Closures</h5>';
   $cartTotals = [34.789, 212.009, 1612.881, 12.89];
   $processedCart = array_map(fn($price) => {
       $delivery = '';
       if ($price > 40) {
           $delivery = 'Free delivery!';
       }
       return '€' . number_format($price, 2, '.', ',') . " $delivery";
   }, $cartTotals);
   print_r($processedCart);
   ```

   *Check: the whole page is replaced by `Parse error: syntax error, unexpected token "{"`.* An arrow function cannot have a block. Replace that `array_map()` call with a closure:

   ```php
   $processedCart = array_map(
       function ($price) {
           $delivery = '';
           if ($price > 40) {
               $delivery = 'Free delivery!';
           }
           return '€' . number_format($price, 2, '.', ',') . " $delivery";
       },
       $cartTotals
   );
   print_r($processedCart);
   ```

   *Check: `Free delivery!` after `€212.01` and `€1,612.88` only.*

5. Follow along: keep the threshold in a variable. Above `$processedCart`, add `$freeThreshold = 40;`, and in the closure change `if ($price > 40)` to `if ($price > $freeThreshold)`.

   *Check: `Warning: Undefined variable $freeThreshold`, and no cart gets free delivery.* The closure cannot see variables from outside it. Change its first line to:

   ```php
   function ($price) use ($freeThreshold) {
   ```

   *Check: free delivery is back on the same two carts.* Change `$freeThreshold` to `1000`. *Check: only `€1,612.88` gets free delivery.* Change it back to `40`.

6. Your turn: create `products.php` in this folder, with:

   ```php
   $products = [
       ['name' => 'Notebook', 'price' => 3.5],
       ['name' => 'Backpack', 'price' => 45],
       ['name' => 'Pen', 'price' => 1.2],
       ['name' => 'Headphones', 'price' => 79.99],
   ];
   $budget = 50;
   ```

   - Use `array_map()` with an arrow function to make a list of just the names, and show it with `implode(', ', ...)`.
   - Use `array_filter($products, $callback)` with a closure and `use ($budget)` to keep only the products that cost `$budget` or less. `array_filter()` keeps each value for which the callback returns `true`.
   - Show the products you can afford as a `<ul>`, with each price formatted as `€3.50`.

   Visit <http://localhost:8000/products.php>. *Check: all four names, then a list of Notebook, Backpack and Pen. Change `$budget` to `5` and only Notebook and Pen are left.*

## Done when

- [ ] `functional.php` shows every section from steps 1 to 5, with no warnings or errors.
- [ ] `products.php` filters the list by `$budget`.
- [ ] You can say when to use an arrow function and when to use a closure.
- [ ] You can say what `use` does, and why an arrow function does not need it.

## Previous / Next

Previous: [03 Return values and scope](../03-scope/README.md). Next: [05 Types, static variables and recursion](../05-types-static-recursion/README.md).
