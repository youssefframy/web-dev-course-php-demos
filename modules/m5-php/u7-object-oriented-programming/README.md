# Unit 7: Object-Oriented Programming

Until now your PHP has been functions and arrays. In this unit you group data and the code that works on it into classes. You build an `Employee` class, extend it, organise it with namespaces, abstract classes, interfaces and traits, handle errors with exceptions, and look at a class from the outside with reflection. Then you use all of it to build a shopping cart.

## What you need

The same dev container as Units 1 to 6. Check PHP in a terminal:

```sh
php -v
```

The first line should start with `PHP 8.3`.

## How to run your pages

Checkpoints 01 to 10 all grow one page, `index.php`, in the unit's [`oop/`](oop) folder. Start the server there:

```sh
cd modules/m5-php/u7-object-oriented-programming/oop
php -S localhost:8000
```

Checkpoints 11 and 12 work on the app in [`shoppingcart/`](shoppingcart). Start the server in that folder instead.

Each of checkpoints 02 to 10 starts from the end of the one before. If you skipped one, its README gives a `cp` command that copies the reference answer into your `oop/` folder.

Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Classes and objects](checkpoints/01-classes-objects/README.md) | A class with properties and a method, and objects made from it |
| 02 | [Encapsulation](checkpoints/02-encapsulation/README.md) | `private` properties, getters and setters, and a rule inside a setter |
| 03 | [Constructors and constants](checkpoints/03-constructors-constants/README.md) | `__construct`, class constants, `stdClass`, and an income statement |
| 04 | [Inheritance](checkpoints/04-inheritance/README.md) | A child class, `parent::`, overriding, `protected` and `instanceof` |
| 05 | [Static members](checkpoints/05-static-members/README.md) | A property and a method that belong to the class itself |
| 06 | [Namespaces](checkpoints/06-namespaces/README.md) | Fixing a name clash, `use`, and aliases |
| 07 | [Abstract classes and interfaces](checkpoints/07-abstract-interfaces/README.md) | Making children provide methods, and code that accepts any of them |
| 08 | [Traits](checkpoints/08-traits/README.md) | Reusing code in classes that are not related |
| 09 | [Exceptions and logging](checkpoints/09-exceptions-logging/README.md) | `try`/`catch`, `throw`, `error_log()` and global handlers |
| 10 | [Reflection](checkpoints/10-reflection/README.md) | Asking PHP about a class while the program runs |
| 11 | [Shopping cart](checkpoints/11-shopping-cart/README.md) | The unit project: `Product`, `Cart` and a working cart page |
| 12 | [Self-study](checkpoints/12-self-study/README.md) | An order summary on the checkout page, and the extra credit: email it |

Checkpoints 01 to 10 build on each other. Checkpoints 11 and 12 are separate from them, and 12 builds on 11.

Reference answers are in [`solutions/`](../../../solutions/modules/m5-php/u7-object-oriented-programming).
