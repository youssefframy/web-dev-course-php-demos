# Checkpoint 08: Traits

## Goal

Write a piece of code once and attach it to a class that is not related to the others.

## Concepts

- A trait is a bundle of properties, methods and constants that a class pulls in with `use`. It acts as if the code were pasted into the class.
- Unlike an interface, a trait comes with working code. Unlike a parent class, a class can use many traits, and the classes that use one need not be related.
- Inside a trait, `$this` and `self` refer to the class that uses it. That is why the trait below can call `getPaySlip()`, a method it does not define. It relies on the using class to provide one. In checkpoint 07 you made it abstract in `Worker`, so the promise is written down.
- Constants in traits need PHP 8.2 or later. The dev container has 8.3.
- Two different `use` keywords exist in PHP. One at the top of a file imports a name from a namespace. One inside a class body attaches a trait.

## Before you start

You need `index.php` and `lib/` from checkpoint 07. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/07-abstract-interfaces/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add the trait to `payroll.php`, above `Worker`.

   ```php
   trait PayslipMetaData
   {
       public const CURRENCY = '€';

       public function getPayslipDetails(): array
       {
           return [
               'currency' => self::CURRENCY,
               'content' => $this->getPaySlip()
           ];
       }
   }
   ```

2. Follow along: attach it to `Worker`, as the first line in the class body.

   ```php
   abstract class Worker
   {
       use PayslipMetaData;
   ```

   Every child of `Worker` now has `getPayslipDetails()`.

3. Follow along: use it in `describeHuman()`, before `return`. The slide has curly quotes around `content`, which PHP does not accept. Use plain quotes.

   ```php
   $ps = $human->getPayslipDetails();
   $result .= "<p>Payslip ({$ps['currency']}): {$ps['content']}</p>";
   ```

   *Check: each worker has a line like `Payslip (€): This is the payslip for the manager.`*

4. Your turn: add a second trait, `Greeting`, with a method `greet(): string` that returns `'Hello, ' . $this->getName()`. Use it in `Worker` too, and show a greeting for each worker.

   *Check: `Hello, Joe`, `Hello, Bob` and `Hello, Judy`.* A class can use several traits: `use PayslipMetaData, Greeting;`.

## Done when

- [ ] Every worker shows a payslip line, and you did not change any child class.
- [ ] You can say one thing a trait can do that an interface cannot, and one thing a trait cannot do that a parent class can.

## Previous / Next

Previous: [07 Abstract classes and interfaces](../07-abstract-interfaces/README.md). Next: [09 Exceptions and logging](../09-exceptions-logging/README.md).
