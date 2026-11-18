# Checkpoint 01: Classes and objects

## Goal

Write a class with properties and a method, create objects from it, and see that each object keeps its own values.

## Concepts

- A class is a template. An object is one thing made from that template, and is called an instance. `Fido` is an instance of `Dog`.
- The things a class holds are its members. A property is a variable inside a class. A method is a function inside a class.
- Class names start with a capital letter and are singular: `Employee`, not `Employees`.
- `new Employee()` makes an object. The `->` operator reaches a property or calls a method on it.
- Inside a method, `$this` is the object the method was called on. This is why `$emp1->getBasicDetailsString()` and `$emp2->getBasicDetailsString()` give different text: the same code runs, but `$this` is a different object each time.
- A property with a type but no value is *uninitialized*. Reading it before you set it is an error.

## Before you start

Create `index.php` in the unit's [`oop/`](../../oop) folder. Start the server there (see the [unit README](../../README.md)). The page is empty at first, so there is nothing to check yet.

## Steps

1. Follow along: write the class and make one object. Start `index.php` with:

   ```php
   <?php

   class Employee
   {
       public string $name;
       public string $surname;
       public string $jobTitle;
       public float $hourlyRate;
       public bool $paidOvertime = false;

       public function getBasicDetailsString(): string
       {
           return "$this->name $this->surname, $this->jobTitle";
       }
   }

   $emp1 = new Employee();
   $emp1->name = 'Alice';
   $emp1->surname = 'Anderson';
   $emp1->jobTitle = 'Head of Technology';
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>OOP in PHP</title>
   </head>

   <body>
       <h1>OOP</h1>
       <p><?= $emp1->getBasicDetailsString() ?></p>
   </body>

   </html>
   ```

   *Check: the page shows `Alice Anderson, Head of Technology`.*

2. Follow along: make a second object, above the `?>`, and show it too.

   ```php
   $emp2 = new Employee();
   $emp2->name = 'Bob';
   $emp2->surname = 'Barker';
   $emp2->jobTitle = 'Chief Marketing Officer';
   ```

   ```php
   <p><?= $emp2->getBasicDetailsString() ?></p>
   ```

   *Check: two lines, one for each employee.* The method is the same, and the output differs because `$this` differs.

3. Follow along: read a property that was never set. Add `<?= $emp1->hourlyRate ?>` to the page.

   *Check: a fatal error saying `$hourlyRate` must not be accessed before initialization.* Remove the line again.

4. Your turn: make a third employee, Claire Curmi, Junior Programmer, with an `hourlyRate` of `21.50`. Show her details and her rate.

   *Check: `Claire Curmi, Junior Programmer, €21.5/hour`.*

## Done when

- [ ] You can say the difference between a class and an object.
- [ ] You can say what `$this` refers to inside a method.
- [ ] Three employees are on the page, each with their own values.

## Previous / Next

Previous: [Unit 6 Working With Arrays](../../../u6-working-with-arrays/README.md). Next: [02 Encapsulation](../02-encapsulation/README.md).
