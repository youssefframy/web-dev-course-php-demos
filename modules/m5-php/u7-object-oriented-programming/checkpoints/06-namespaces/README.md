# Checkpoint 06: Namespaces

## Goal

Add a third-party library that has a class with the same name as yours, and make both work side by side.

## Concepts

- A namespace is a named group of classes, functions and constants, like a folder for names. It prevents two classes called `Employee` from clashing.
- Code with no `namespace` line is in the *global namespace*.
- A `namespace` line must be the first statement in the file. From then on, every name in the file belongs to it, and PHP looks for the classes you mention in that same namespace.
- PHP's own classes, such as `stdClass`, are global. In a namespaced file, write a leading backslash: `\stdClass`. Functions and constants like `count()` fall back to the global ones by themselves, but classes do not.
- By convention a library uses its owner's domain turned around, such as `com\icemalta\libpayroll`, and the file sits in a folder path that matches.
- `use some\long\Name as Short;` lets you write `Short` instead of the full name.

## Before you start

You need `index.php` from checkpoint 05. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/05-static-members/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: create the library in `oop/lib/payroll.php`. It is a stand-in for code you downloaded.

   ```php
   <?php
   class Manager
   {
       public function getPaySlip(): string
       {
           return 'This is the payslip for the manager.';
       }
   }

   class Employee
   {
       public function getPaySlip(): string
       {
           return 'This is the payslip for the Employee.';
       }
   }

   class Contractor
   {
       public function getPaySlip(): string
       {
           return 'This is the payslip for the contractor.';
       }
   }
   ```

2. Follow along: load it at the top of `index.php`, just under `<?php`.

   ```php
   require 'lib/payroll.php';
   ```

   *Check: a fatal error, `Cannot declare class Employee, because the name is already in use`.* That is the clash.

3. Follow along: put `index.php` in its own namespace. This line goes straight under `<?php`, above the `require`.

   ```php
   namespace unit7;
   ```

   *Check: a fatal error that class `unit7\stdClass` is not found.* PHP now looks for `stdClass` in `unit7`. Write `new \stdClass()` in `getIncomeStatement()`. *Check: the page works again.*

4. Follow along: use the library's `Employee`. Add a section at the end of the page.

   ```php
   <h3>Using the Payroll Library</h3>
   <?php
   $joe = new \Employee();
   echo "<p>{$joe->getPaySlip()}</p>";
   ?>
   ```

   *Check: `This is the payslip for the Employee.`* A bare `new Employee()` would have built your own class, from `unit7`. The leading backslash asks for the global one.

5. Follow along: give the library a namespace too, so it cannot clash with anything. Add this under `<?php` in `payroll.php`:

   ```php
   namespace com\icemalta\libpayroll;
   ```

   Move the file to `oop/lib/com/icemalta/libpayroll/payroll.php` so the path matches the namespace, and change the `require` in `index.php` to match. *Check: a fatal error that `\Employee` is not found.*

6. Follow along: use an alias. Under the `require`:

   ```php
   use com\icemalta\libpayroll\{
       Employee as PayrollEmployee,
       Manager as PayrollManager,
       Contractor as PayrollContractor
   };
   ```

   Change the section to loop over one of each.

   ```php
   $humans = [new PayrollEmployee(), new PayrollManager(), new PayrollContractor()];
   foreach ($humans as $human) {
       echo "<p>{$human->getPaySlip()}</p>";
   }
   ```

   *Check: three payslip lines, and your own employee list above them still works.*

7. Your turn: add `use com\icemalta\libpayroll\Employee;` without an alias.

   *Check: a fatal error, `Cannot use ... Employee because the name is already in use`.* Your own `Employee` is in the same namespace. That is why aliases exist. Remove the line.

## Done when

- [ ] Your `Employee` and the library's `Employee` both work on the page.
- [ ] You can say why `stdClass` needs a leading backslash in a namespaced file.
- [ ] The library file's path matches its namespace.

## Previous / Next

Previous: [05 Static members](../05-static-members/README.md). Next: [07 Abstract classes and interfaces](../07-abstract-interfaces/README.md).
