# Checkpoint 10: Reflection

## Goal

Ask PHP about classes and objects while the program runs: what a class is called, what its parent is, and what it can do.

## Concepts

- Introspection means code that examines code. PHP has small functions for the basics, and a `ReflectionClass` for the rest.
- `class_exists()`, `method_exists()`, `get_class()`, `get_parent_class()`, `get_class_methods()`, `get_object_vars()` and `is_object()` are the quick ones.
- These functions only show what the calling code could see. Called from outside a class, `get_class_methods()` lists public methods and `get_object_vars()` lists public properties. Asking from inside the class shows more.
- A class name given as a string is **not** resolved against the current namespace. After checkpoint 06 `class_exists('Employee')` is false, because the class is really `unit7\Employee`. Use `Employee::class`, which gives the full name.
- `ReflectionClass` sees everything, including private members, which is why it is used by frameworks and testing tools rather than everyday code.

## Before you start

You need `index.php` and `lib/` from checkpoint 09. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/09-exceptions-logging/oop/. modules/m5-php/u7-object-oriented-programming/oop/
```

## Steps

1. Follow along: add a section at the end of the page.

   ```php
   <h3>Reflection</h3>
   <pre><?php
   $lead = $emp4;
   echo 'class_exists(TeamLead::class): ';
   var_dump(class_exists(TeamLead::class));
   echo 'get_class($lead): ', get_class($lead), "\n";
   echo 'get_parent_class($lead): ', get_parent_class($lead), "\n";
   echo 'method_exists($lead, getBonus): ';
   var_dump(method_exists($lead, 'getBonus'));
   ?></pre>
   ```

   *Check: `bool(true)`, `unit7\TeamLead`, `unit7\Employee`, `bool(true)`.*

2. Follow along: try the string form. Add `var_dump(class_exists('Employee'));` and then `var_dump(class_exists('\\unit7\\Employee'));`.

   *Check: `bool(false)`, then `bool(true)`.*

3. Follow along: list what you can see from outside.

   ```php
   print_r(get_class_methods(TeamLead::class));
   print_r(get_object_vars($lead));
   ```

   *Check: the method list has the public methods of `TeamLead` and `Employee`, but not `getTaxAmount`. The second list is empty: every property is private or protected.*

4. Follow along: ask with reflection.

   ```php
   $rc = new \ReflectionClass(TeamLead::class);
   echo 'Short name: ', $rc->getShortName(), "\n";
   echo 'Parent: ', $rc->getParentClass()->getName(), "\n";
   foreach ($rc->getProperties() as $property) {
       echo ' - ', $property->getName(), $property->isPrivate() ? ' (private)' : '', $property->isProtected() ? ' (protected)' : '', "\n";
   }
   ```

   *Check: `TeamLead`, `unit7\Employee`, then `bonusEntitlement` and `hourlyRate`, both protected.* The private properties of `Employee` are missing: a child does not inherit private members.

5. Your turn: use reflection to print the names of all the methods of `Employee`, including the private `getTaxAmount()`. Look up `ReflectionClass::getMethods()`.

   *Check: `getTaxAmount` is in the list.*

## Done when

- [ ] You can say why `class_exists('Employee')` was false.
- [ ] You can say why `get_object_vars()` returned an empty list.
- [ ] You can name one thing reflection can see that the simple functions cannot.

## Previous / Next

Previous: [09 Exceptions and logging](../09-exceptions-logging/README.md). Next: [11 Shopping cart](../11-shopping-cart/README.md).
