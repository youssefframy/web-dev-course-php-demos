# Checkpoint 07: Abstract classes and interfaces

## Goal

Make the library's classes share a parent that forces each child to describe its role, add an interface for staff only, and write one function that works for all of them.

## Concepts

- An abstract class cannot be turned into an object with `new`. It exists to be a parent. It may contain real methods and properties, and also *abstract methods*: a name and a signature with no body, which every child must write.
- An interface is a list of methods a class promises to have. Every method is public and has no body. A class `implements` an interface, and may implement several.
- A class has only one parent, but can implement many interfaces. This is the main reason to choose an interface over an abstract class.
- A function whose parameter is typed with the abstract class accepts any child. Inside it, you can call the methods the parent promises.
- Use `instanceof` with an interface name to ask whether an object can do something extra.
- Interfaces can also hold constants, even though the slides only mention methods.

## Before you start

You need `index.php` and `lib/` from checkpoint 06. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/06-namespaces/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: in `payroll.php`, add an abstract parent above the other classes. It owns the name and surname.

   ```php
   abstract class Worker
   {
       private string $name;
       private string $surname;

       public function __construct(string $name, string $surname)
       {
           $this->name = $name;
           $this->surname = $surname;
       }

       abstract public function getRoleDescription(): string;

       abstract public function getPaySlip(): string;

       // getName(), setName(), getSurname() and setSurname() as usual
   }
   ```

   The slides leave out the second abstract method. Adding it means every worker is promised a payslip, which checkpoint 08 needs.

2. Follow along: make each class a child, and add the role.

   ```php
   class Manager extends Worker
   {
       public function getRoleDescription(): string
       {
           return 'This is a manager';
       }
       // getPaySlip() stays as it was
   }
   ```

   Do the same for `Employee` ('This is an Employee') and `Contractor` ('This is a Contractor'). Your editor's quick fix can write the method stubs.

3. Follow along: try `new Worker('A', 'B')` in `index.php`.

   *Check: a fatal error, `Cannot instantiate abstract class`.* Remove it.

4. Follow along: add the interface to `payroll.php`, and implement it in `Manager` and `Employee` only.

   ```php
   interface StaffMember
   {
       public function getPerksString(): string;
       public function getHealthBenefitsString(): string;
   }

   class Manager extends Worker implements StaffMember
   ```

   Give the manager 'Retirement contribution, 24/7 Gym, Meals, Business-Class Travel' and health 'Full private hospital, dental, cosmetic'. Give the employee the same without 'Business-Class Travel' and 'cosmetic'.

5. Follow along: one function for all workers, in `index.php`. Add `Worker as PayrollWorker` and `StaffMember` to the `use` list.

   ```php
   function describeHuman(PayrollWorker $human): string
   {
       $result = sprintf(
           '<p>%s %s (%s)</p>',
           htmlspecialchars($human->getName()),
           htmlspecialchars($human->getSurname()),
           $human->getRoleDescription()
       );
       if ($human instanceof StaffMember) {
           $result .= "<p>Health: {$human->getHealthBenefitsString()}</p>";
           $result .= "<p>Perks: {$human->getPerksString()}</p>";
       }
       return $result;
   }
   ```

   The slide has a curly quote around the `sprintf` format, which PHP does not accept. Use plain quotes.

   Update the loop to build the workers with names and print `describeHuman($human)`:

   ```php
   $humans = [
       new PayrollEmployee('Joe', 'Doe'),
       new PayrollManager('Bob', 'Brown'),
       new PayrollContractor('Judy', 'Judge')
   ];
   ```

   *Check: three entries. Joe and Bob show Health and Perks lines. Judy, the contractor, does not.*

6. Your turn: add a `Director` that extends `Worker` and implements `StaffMember`. Add one to the list.

   *Check: it appears with its own role, and with health and perks lines, and `describeHuman()` needed no change.*

## Done when

- [ ] `new Worker(...)` is refused.
- [ ] Every worker is described by one function, and only staff show perks.
- [ ] You can say why a class can implement two interfaces but extend only one class.

## Previous / Next

Previous: [06 Namespaces](../06-namespaces/README.md). Next: [08 Traits](../08-traits/README.md).
