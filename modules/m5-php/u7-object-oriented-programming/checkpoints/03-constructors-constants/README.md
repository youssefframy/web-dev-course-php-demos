# Checkpoint 03: Constructors and constants

## Goal

Create objects in one line with a constructor, keep fixed values in class constants, and produce an income statement with `stdClass`.

## Concepts

- The constructor is a method named `__construct()`. PHP runs it as soon as `new` makes the object, so it is the place to set starting values.
- A constructor parameter with a default value is optional, like any function parameter.
- A class constant is made with `const`. By convention its name is in capitals. It can be `public` or `private`, like other members.
- You read a constant through the class, not an object: `Employee::CURRENCY` from outside, `self::CURRENCY` from inside. There is no `$`, and the operator is `::` (the scope resolution operator), not `->`.
- `stdClass` is a built-in empty class that accepts properties you invent as you go. It is handy for returning several values together.
- A tax bracket table gives, for each band of income, a rate and an amount to subtract. The tax is `gross * rate / 100 - subtract`. The subtract amount is what makes the tax continuous where one band ends and the next starts.

## Before you start

You need `index.php` from checkpoint 02. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/02-encapsulation/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add the constructor under the properties. Call the setter for the rate, so the rule from checkpoint 02 still applies.

   ```php
   public function __construct(string $name, string $surname, string $jobTitle, float $hourlyRate = 0, bool $paidOvertime = false)
   {
       $this->name = $name;
       $this->surname = $surname;
       $this->jobTitle = $jobTitle;
       $this->setHourlyRate($hourlyRate);
       $this->paidOvertime = $paidOvertime;
   }
   ```

   Reload. *Check: a fatal error, because `new Employee()` now gives too few arguments.* Replace the creating code:

   ```php
   $emp1 = new Employee('Alice', 'Anderson', 'Head of Technology');
   $emp2 = new Employee('Bob', 'Barker', 'Chief Marketing Officer');
   $emp3 = new Employee('Claire', 'Curmi', 'Junior Programmer', 21.50);
   $emp4 = new Employee('Dave', 'Doddleston', 'Lead Programmer', 30, true);
   ```

   Because the setter now ignores a rate of zero or less, the default of 0 means "no valid rate yet". Change the check in `setHourlyRate()` to `$hourlyRate >= 0` so the default is accepted.

2. Follow along: add the constants at the top of the class.

   ```php
   public const CURRENCY = '€';
   private const YEAR_HOURS_STANDARD = 1920;
   private const TAX_BRACKETS = [
       ['from' => 0, 'rate' => 0, 'subtract' => 0],
       ['from' => 9101, 'rate' => 15, 'subtract' => 1365],
       ['from' => 14501, 'rate' => 25, 'subtract' => 2815],
       ['from' => 19501, 'rate' => 25, 'subtract' => 2725],
       ['from' => 60001, 'rate' => 35, 'subtract' => 8725]
   ];
   ```

   Show the currency on the page: `<?= Employee::CURRENCY ?>`. *Check: `€`.* Then try `<?= Employee::TAX_BRACKETS ?>`. *Check: a fatal error, because it is private.* Remove it.

3. Follow along: add the tax calculation and the income statement.

   ```php
   private function getTaxAmount(float $grossWage): float
   {
       // Look for the highest bracket that starts at or below the wage
       foreach (array_reverse(self::TAX_BRACKETS) as $bracket) {
           if ($grossWage >= $bracket['from']) {
               return $grossWage * $bracket['rate'] / 100 - $bracket['subtract'];
           }
       }
       return 0; // a negative wage pays no tax
   }

   public function getIncomeStatement(float $hoursWorked): object
   {
       $standardHours = min($hoursWorked, self::YEAR_HOURS_STANDARD);
       $overtimeHours = max($hoursWorked - self::YEAR_HOURS_STANDARD, 0);

       $wageDetail = new stdClass();
       $wageDetail->standardHours = $standardHours;
       $wageDetail->overtimeHours = $this->paidOvertime ? $overtimeHours : 0;
       $wageDetail->standardGross = $standardHours * $this->hourlyRate;
       $wageDetail->overtimeGross = $this->paidOvertime ? $overtimeHours * $this->hourlyRate * 1.5 : 0;
       $wageDetail->totalGross = $wageDetail->standardGross + $wageDetail->overtimeGross;
       $wageDetail->totalTax = $this->getTaxAmount($wageDetail->totalGross);
       $wageDetail->totalNet = $wageDetail->totalGross - $wageDetail->totalTax;
       return $wageDetail;
   }
   ```

   The slides subtract first and then multiply, which gives a wrong answer for every bracket with a subtract amount. They also leave a gap between brackets, so a wage of 9100.50 matches none. Looking for the highest bracket that starts at or below the wage closes the gap.

4. Follow along: show Dave's statement in a table. Use `number_format($value, 2)` for the money.

   ```php
   <?php
   $payslip = $emp4->getIncomeStatement(2000);
   $c = Employee::CURRENCY;
   $f = fn(float $n): string => $c . number_format($n, 2);
   ?>
   <table border="1">
       <tr><td>Gross Wage</td><td><?= $f($payslip->totalGross) ?></td></tr>
       <tr><td>Tax Due</td><td><?= $f($payslip->totalTax) ?></td></tr>
       <tr><td>Net Wage</td><td><?= $f($payslip->totalNet) ?></td></tr>
   </table>
   ```

   Add rows for the other fields of the statement as well.

   *Check: Dave works 2000 hours at €30, with 80 hours of overtime at 1.5 times. Gross €61,200.00, tax €12,695.00, net €48,505.00.*

5. Your turn: show Claire's statement for 1000 hours.

   *Check: gross €21,500.00, tax €2,650.00 (21,500 × 25% − 2,725), net €18,850.00.*

## Done when

- [ ] Every employee is made in one line.
- [ ] `Employee::CURRENCY` works from the page, and the private constants do not.
- [ ] Dave's income statement matches the numbers above.
- [ ] You can say why a constructor parameter with a default is optional.

## Previous / Next

Previous: [02 Encapsulation](../02-encapsulation/README.md). Next: [04 Inheritance](../04-inheritance/README.md).
