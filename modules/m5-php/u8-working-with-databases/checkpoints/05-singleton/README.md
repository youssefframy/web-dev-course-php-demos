# Checkpoint 05: The Singleton pattern

## Goal

Write a `DBConnect` class that gives every part of a page the same single database connection, and use it from three pages.

## Concepts

- A design pattern is a known, tested answer to a problem that comes up again and again. You use it the way an engineer uses a known bridge design, instead of inventing one.
- The problem: if each class or page makes its own `new PDO(...)`, one visit opens several connections to the database server. That is wasted work for the server.
- The Singleton pattern: a class that can only ever have **one** instance. Three parts make it:
  1. A `private` constructor. No other code can write `new DBConnect()`.
  2. A `private static` property that holds the one instance.
  3. A `public static` method, `getInstance()`, that creates the instance the first time and returns the same one every time after.
- `??` (null coalescing) does the "create it if it is `null`" in one line: `self::$singleton = self::$singleton ?? new DBConnect();`.
- Limits to know about: the instance lives for **one request**. When the visitor goes to another page, a new request starts and there is a new instance and a new connection (the persistent connection from checkpoint 01 is what saves you there). And a connection cannot be stored in the session, because PHP cannot serialize a PDO object.

## Before you start

Rename your checkpoint 04 page. You create four files in the unit's [`pdo/`](../../pdo) folder: `DBConnect.php`, `index.php`, `short.php` and `long.php`.

## Steps

1. Follow along: write `DBConnect.php`.

   ```php
   <?php
   class DBConnect
   {
       private static ?DBConnect $singleton = null;
       private PDO $dbh;

       // Private: no other file can write `new DBConnect()`.
       private function __construct()
       {
           $host = getenv('DB_HOST') ?: '127.0.0.1';
           $this->dbh = new PDO(
               "mysql:host=$host;dbname=shoppingcart;charset=utf8mb4",
               'root',
               'root',
               [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true]
           );
       }

       public static function getInstance(): DBConnect
       {
           self::$singleton = self::$singleton ?? new DBConnect();
           return self::$singleton;
       }

       public function getConnection(): PDO
       {
           return $this->dbh;
       }
   }
   ```

2. Follow along: use it in `index.php`.

   ```php
   <?php
   require 'DBConnect.php';
   $conn = DBConnect::getInstance()->getConnection();

   $sth = $conn->prepare('SELECT COUNT(Product.id) AS prodCount FROM Product');
   $sth->execute();
   $result = $sth->fetch(PDO::FETCH_OBJ);
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>Courses</title>
   </head>

   <body>
       <p>We have <?= $result->prodCount ?> courses currently available.</p>
       <p>View our <a href="short.php">short</a> or <a href="long.php">long</a> courses.</p>
   </body>

   </html>
   ```

   *Check: `We have 4 courses currently available.` with two links.*

3. Follow along: try to break the rule. Add `<?php $x = new DBConnect(); ?>` to `index.php`.

   *Check: a fatal error, `Call to private DBConnect::__construct() from global scope`.* Remove it.

4. Follow along: prove there is only one. Add to the body:

   ```php
   <p>Same object on every call: <?= var_export(DBConnect::getInstance() === DBConnect::getInstance(), true) ?></p>
   ```

   *Check: `true`.*

5. Follow along: write `short.php`. Short courses are the ones with no deposit.

   ```php
   <?php
   require 'DBConnect.php';
   $conn = DBConnect::getInstance()->getConnection();

   $sth = $conn->prepare('SELECT Product.name, Product.price FROM Product WHERE requiresDeposit = :deposit');
   $sth->bindValue('deposit', false, PDO::PARAM_BOOL);
   $sth->execute();
   $result = $sth->fetchAll(PDO::FETCH_OBJ);
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>Our Short Courses</title>
   </head>

   <body>
       <h1>Our Short Courses</h1>
       <?php foreach ($result as $row) { ?>
           <p>Course: <?= htmlspecialchars($row->name) ?>, Price: &euro;<?= number_format($row->price, 2) ?></p>
       <?php } ?>
       <p><a href="index.php">Back</a></p>
   </body>

   </html>
   ```

   *Check: three courses at €90.00.*

6. Your turn: write `long.php` for the courses that **do** require a deposit.

   *Check: one course, `Game Development with Unity`, €360.00.*

7. Your turn: look at the connections. In `./sql shell shoppingcart`, run `SHOW GLOBAL STATUS LIKE 'Connections';`, load `index.php`, `short.php` and `long.php` once each, and run it again.

   *Check: it rose by at most 1 (plus 1 for your shell), because of the persistent connection, even though each page created its own `DBConnect`. Say in your own words which of the two, the Singleton or the persistent connection, saved the connection between pages.*

## Done when

- [ ] `index.php`, `short.php` and `long.php` all work.
- [ ] You can name the three parts that make a Singleton.
- [ ] You can say why one `DBConnect` per page is the best a Singleton can do here.

## Previous / Next

Previous: [04 Insert, update, delete](../04-insert-update-delete/README.md). Next: [06 Cart: database and model](../06-cart-database-and-model/README.md).
