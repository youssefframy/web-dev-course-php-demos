# Checkpoint 02: Encapsulation

## Goal

Make the properties of `Employee` private, give access through getters and setters, and put a rule inside a setter.

## Concepts

- Every member has a visibility. `public` means anywhere. `protected` means inside the class and its children. `private` means inside the class only.
- While a property is public, any code can put any value in it. The class has no say.
- Encapsulation means keeping the data private and letting the outside reach it only through methods. Make a property `private` unless you have a reason not to.
- A getter reads a property. A setter changes it. The setter is the one place to refuse bad values.
- A setter that ends with `return $this;` lets calls be chained: `$emp->setName('Bob')->setSurname('Barker')`.
- The type on a parameter already does some of the checking. `setHourlyRate(float $rate)` refuses `'Banana'` with a `TypeError` before the body of the method runs. So the rules you write yourself are for what a type cannot say, such as "greater than zero".

## Before you start

You need `index.php` from checkpoint 01. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/01-classes-objects/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: make the five properties `private`. Reload.

   *Check: a fatal error about accessing a private property.* The page code now has no way in.

2. Follow along: add a getter and a setter for `name`, inside the class.

   ```php
   public function getName(): string
   {
       return $this->name;
   }

   public function setName(string $name): self
   {
       $this->name = $name;
       return $this;
   }
   ```

   Change `$emp1->name = 'Alice';` to `$emp1->setName('Alice');`.

3. Follow along: do the same for `surname`, `jobTitle`, `hourlyRate` and `paidOvertime`. The PHP Tools extension can write them for you: select the properties, click the lightbulb, and choose *Add getters and setters*. Use `isPaidOvertime()` as the getter for the boolean. Change every assignment in the page to use a setter, and chain the calls for Bob.

   ```php
   $emp2->setName('Bob')->setSurname('Barker')->setJobTitle('Chief Marketing Officer');
   ```

   *Check: the page looks as it did in checkpoint 01.*

4. Follow along: add a rule. A rate of zero or less makes no sense, so make `setHourlyRate()` ignore it:

   ```php
   public function setHourlyRate(float $hourlyRate): self
   {
       if ($hourlyRate > 0) {
           $this->hourlyRate = $hourlyRate;
       }
       return $this;
   }
   ```

   Also give the property a starting value, `private float $hourlyRate = 0;`, so reading it never fails. Add a method that uses it:

   ```php
   public function getGrossPay(float $hoursWorked): float
   {
       return $hoursWorked * $this->hourlyRate;
   }
   ```

5. Your turn: set Claire's rate to `21.50`, then call `setHourlyRate(-5)`. Show her pay for 160 hours.

   *Check: `3440`. The negative rate was refused and the old rate stayed.*

6. Follow along: try a bad type. Call `$emp3->setHourlyRate('Banana');`.

   *Check: a `TypeError` saying the argument must be of type `float`, `string` given.* Remove the line.

7. Optional: `declare(strict_types=1);` makes PHP stricter about types, for example a `'5'` is no longer accepted for a `float`. It must be the first statement in a file, and it affects the whole file. Try it in a separate file, [`strict.php`](../../../../../solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/02-encapsulation/oop/strict.php), and **do not add it to `index.php`**: in checkpoint 09 the form sends numbers as text, and a strict file would reject them.

## Done when

- [ ] All five properties are private and every access goes through a method.
- [ ] A negative rate is ignored.
- [ ] You can say why a private property with a setter is safer than a public property.

## Previous / Next

Previous: [01 Classes and objects](../01-classes-objects/README.md). Next: [03 Constructors and constants](../03-constructors-constants/README.md).
