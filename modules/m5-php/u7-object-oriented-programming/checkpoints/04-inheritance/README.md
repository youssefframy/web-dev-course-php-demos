# Checkpoint 04: Inheritance

## Goal

Make `TeamLead` a child of `Employee`, override a method, and share code between parent and child without testing for the child's type.

## Concepts

- A child class `extends` a parent and gets all its properties and methods. The child adds to them or replaces some.
- A child's constructor must call the parent's constructor, `parent::__construct(...)`. PHP has to build the `Employee` part before the `TeamLead` part.
- Overriding means writing a method in the child with the same name, parameters and return type as one in the parent. Calls on a child object use the child's version. This is polymorphism: the same call, different behaviour depending on the object.
- `private` members are invisible to children. `protected` members are visible to the class and its children, and to nobody else.
- `$object instanceof TeamLead` is true when the object is a `TeamLead`, or a child of it.
- A parent that has to ask `instanceof` about its own children is a warning sign. Every new child means editing the parent. A better design is a method that the child overrides. You do this in step 6.

## Before you start

You need `index.php` from checkpoint 03. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/03-constructors-constants/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add the child class below `Employee`.

   ```php
   class TeamLead extends Employee
   {
       private float $bonusEntitlement = 0;

       public function __construct(string $name, string $surname, string $jobTitle, float $hourlyRate = 0, bool $paidOvertime = false, float $bonusEntitlement = 0)
       {
           parent::__construct($name, $surname, $jobTitle, $hourlyRate, $paidOvertime);
           $this->bonusEntitlement = $bonusEntitlement;
       }

       public function getBonusEntitlement(): float
       {
           return $this->bonusEntitlement;
       }

       public function setBonusEntitlement(float $bonusEntitlement): self
       {
           $this->bonusEntitlement = $bonusEntitlement;
           return $this;
       }
   }
   ```

   Make Dave a team lead with a bonus of 4000: `$emp4 = new TeamLead('Dave', 'Doddleston', 'Lead Programmer', 30, true, 4000);`

2. Follow along: show Dave's pay for a full year, using the parent's method.

   ```php
   <p><?= $emp4->getBasicDetailsString() ?>, pay for 1920 hours: €<?= number_format($emp4->getGrossPay(1920), 2) ?></p>
   ```

   *Check: €57,600.00.* That is wrong: Dave also earns a €4,000 bonus, so €61,600.00. The inherited method knows nothing about it.

3. Follow along: override `getGrossPay()` inside `TeamLead`.

   ```php
   public function getGrossPay(float $hoursWorked): float
   {
       return ($hoursWorked * $this->hourlyRate) + $this->bonusEntitlement;
   }
   ```

   *Check: a warning `Undefined property: TeamLead::$hourlyRate`, and a pay of €4,000.00, because the rate counts as 0.* The parent's private property is hidden from the child. In `Employee`, change `private float $hourlyRate` to `protected float $hourlyRate`.

   *Check: €61,600.00.*

4. Follow along: Dave's income statement still ignores the bonus. Make `$bonusEntitlement` `protected`, and add one line to `getIncomeStatement()` in `Employee`, below the `standardGross` line:

   ```php
   $wageDetail->standardGross += $this instanceof TeamLead ? $this->bonusEntitlement : 0;
   ```

   *Check: gross €65,200.00.* It works, but `Employee` now has to know about `TeamLead`.

5. Your turn: a `Director` would also have a bonus. What would you have to change in `Employee` to support it? Write your answer down before step 6.

6. Follow along: remove the `instanceof` line and the `getGrossPay()` override, and use a method instead. In `Employee`:

   ```php
   public function getBonus(): float
   {
       return 0;
   }

   public function getGrossPay(float $hoursWorked): float
   {
       return $hoursWorked * $this->hourlyRate + $this->getBonus();
   }
   ```

   In `getIncomeStatement()`, use it: `$wageDetail->standardGross = $standardHours * $this->hourlyRate + $this->getBonus();`

   In `TeamLead`:

   ```php
   public function getBonus(): float
   {
       return $this->bonusEntitlement;
   }
   ```

   *Check: the pay for 1920 hours is still €61,600.00, the gross in the statement is still €65,200.00, and `Employee` no longer mentions `TeamLead`.* A `Director` now only has to override `getBonus()`.

## Done when

- [ ] Dave's pay for 1920 hours is €61,600.00 and his statement gross is €65,200.00.
- [ ] You can say why `TeamLead`'s constructor calls `parent::__construct()`.
- [ ] You can say the difference between `private` and `protected`.
- [ ] You can say why step 6 is a better design than step 4.

## Previous / Next

Previous: [03 Constructors and constants](../03-constructors-constants/README.md). Next: [05 Static members](../05-static-members/README.md).
