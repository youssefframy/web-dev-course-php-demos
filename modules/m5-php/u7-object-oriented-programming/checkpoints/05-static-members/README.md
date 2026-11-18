# Checkpoint 05: Static members

## Goal

Keep a list of every employee ever created, in a property that belongs to the class itself, and show it in a table.

## Concepts

- A static member belongs to the class, not to any object. You reach it with `::` and need no object.
- A static property has one value, shared by every object. If one object changes it, they all see the change.
- Inside the class, a static property is `self::$employeeList`. Unlike a constant, it has a `$`. From outside it would be `Employee::$employeeList`, if it were public.
- A static method is called with `Employee::getEmployees()`. It has no `$this`, because no object is involved.
- Static state lasts only for the one request. When the page finishes, the list is gone.

## Before you start

You need `index.php` from checkpoint 04. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/04-inheritance/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add the static property under the constants of `Employee`.

   ```php
   private static array $employeeList = [];
   ```

2. Follow along: add the new employee to it, as the last line of the constructor.

   ```php
   self::$employeeList[] = $this;
   ```

   `TeamLead` calls the parent constructor, so team leads are added too.

3. Follow along: add a static getter, because the list is private.

   ```php
   public static function getEmployees(): array
   {
       return self::$employeeList;
   }
   ```

4. Follow along: list the employees on the page.

   ```php
   <h3>List of Employees</h3>
   <table border="1">
       <thead><tr><th>Name</th><th>Surname</th><th>Job Title</th><th>Type</th></tr></thead>
       <tbody>
           <?php foreach (Employee::getEmployees() as $employee) { ?>
               <tr>
                   <td><?= $employee->getName() ?></td>
                   <td><?= $employee->getSurname() ?></td>
                   <td><?= $employee->getJobTitle() ?></td>
                   <td><?= $employee instanceof TeamLead ? 'Team Lead' : 'Employee' ?></td>
               </tr>
           <?php } ?>
       </tbody>
   </table>
   ```

   *Check: four rows. Dave's type is `Team Lead`, the others `Employee`.* Here `instanceof` is fine: it is the page asking about a type, not a parent asking about its children.

5. Your turn: add a fifth employee, below the table, and reload.

   *Check: nothing new appears.* The table is above the new `new`, so the list had four when it was printed. Move the employee above the table. *Check: five rows.*

6. Your turn: add a static method `Employee::getEmployeeCount()` that returns how many employees exist, and show the count above the table.

   *Check: `Employees: 5`.*

## Done when

- [ ] The table shows every employee created, including the team lead.
- [ ] You can say why `getEmployees()` can be called without an object.
- [ ] You can say why the list is empty again on the next request.

## Previous / Next

Previous: [04 Inheritance](../04-inheritance/README.md). Next: [06 Namespaces](../06-namespaces/README.md).
