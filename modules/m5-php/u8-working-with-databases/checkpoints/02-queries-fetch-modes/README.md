# Checkpoint 02: Queries and fetch modes

## Goal

Run a `SELECT` with `query()` and fetch the rows as arrays, objects, a single column, instances of your own class, and the result of a function.

## Concepts

- `$dbh->query($sql)` prepares and runs a statement in one step. It returns a `PDOStatement`. It runs any SQL, not just `SELECT`. Use it for fixed statements with no values from a visitor. Checkpoint 03 covers the safe way to send values.
- `fetchAll()` returns every row. What a row looks like depends on the fetch mode:
  - `PDO::FETCH_BOTH` (the default): an array with every column twice, once by name and once by position. Almost never what you want.
  - `PDO::FETCH_ASSOC`: an array keyed by column name.
  - `PDO::FETCH_OBJ`: a `stdClass` object, with one property per column.
  - `PDO::FETCH_COLUMN`: a flat array of one column. A second argument says which one, counting from 0.
  - `PDO::FETCH_CLASS`: an instance of a class you name, with the columns copied into its properties.
  - `PDO::FETCH_FUNC`: calls a function once per row with the columns as arguments, and collects what it returns.
- `query()` also takes the fetch mode as its second argument. The statement can then be used straight in a `foreach`, with no `fetchAll()`.
- A `DECIMAL` column such as `price` comes back as a string or a float depending on the PHP and driver settings, so cast or format it before you do sums.

## Before you start

You need the database from the [unit README](../../README.md). Keep your checkpoint 01 page by renaming it (for example `index-01.php`), then create a new `index.php` in the unit's [`pdo/`](../../pdo) folder.

## Steps

1. Follow along: connect as in checkpoint 01, run a query, and dump the first row in the default mode.

   ```php
   <?php
   $host = getenv('DB_HOST') ?: '127.0.0.1';

   try {
       $dbh = new PDO("mysql:host=$host;dbname=shoppingcart;charset=utf8mb4", 'root', 'root', [
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_PERSISTENT => true,
       ]);

       $both = $dbh->query('SELECT * FROM Product')->fetchAll();
   } catch (PDOException $e) {
       exit('Database error: ' . htmlspecialchars($e->getMessage()));
   }
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>Fetch modes</title>
   </head>

   <body>
       <h1>Fetch modes</h1>

       <h2>FETCH_BOTH (the default): one row</h2>
       <pre><?php var_dump($both[0]); ?></pre>
   </body>

   </html>
   ```

   *Check: the row appears twice over: `'id' => 1` and `0 => 1`, `'name' => …` and `1 => …`.*

2. Follow along: fetch with names only, and as objects. Each statement is run again, because a result can only be read once. Add above the `catch`:

   ```php
   $assoc = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_ASSOC);
   $objects = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_OBJ);
   ```

   and show each with `var_dump($assoc[0])` and `var_dump($objects[0])` in the body.

   *Check: six keys with `FETCH_ASSOC`, and `object(stdClass)` with six properties for `FETCH_OBJ`.*

3. Follow along: fetch single columns. Column `1` is `name` and column `2` is `price`.

   ```php
   $names = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_COLUMN, 1);
   $prices = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_COLUMN, 2);
   ```

   *Check: `var_dump($names, $prices)` gives two arrays of four values.*

4. Follow along: fetch into your own class. Above the `try`, add a class whose properties match the columns:

   ```php
   class Product
   {
       public $id;
       public $name;
       public $price;
       public $description;
       public $featuredImage;
       public $requiresDeposit;
   }
   ```

   then `$products = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_CLASS, 'Product');`

   *Check: `var_dump($products[0])` says `object(Product)`.*

5. Follow along: call a function for every row. The parameters take the columns in order.

   ```php
   function describe(int $id, string $name, float $price, string $description, ?string $featuredImage, int $requiresDeposit): string
   {
       return sprintf(
           'Product (%d) %s costing €%.2f: %s... Featured image: %s. %s',
           $id,
           $name,
           $price,
           substr($description, 0, 10),
           $featuredImage,
           $requiresDeposit ? 'This product requires a deposit' : ''
       );
   }
   ```

   ```php
   $lines = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_FUNC, 'describe');
   ```

   Show each line in a `<p>`, escaped with `htmlspecialchars()`.

   *Check: four lines, the Unity one ending `This product requires a deposit`.*

6. Follow along: loop over the statement itself, with the mode as the second argument.

   ```php
   $loop = $dbh->query('SELECT * FROM Product', PDO::FETCH_OBJ);
   ```

   ```php
   <?php foreach ($loop as $row) { ?>
       <p><?= htmlspecialchars($row->name) ?></p>
   <?php } ?>
   ```

   *Check: the four course names.*

7. Your turn: show only the courses that cost 90 or less, ordered by name, in `FETCH_ASSOC` mode, as a list of `name (€price)`. Use `number_format($row['price'], 2)`.

   *Check: three courses, from AI with TensorFlow & Python to Introduction to CSS FlexBox, none of them Unity.*

## Done when

- [ ] You can say what `FETCH_BOTH` gives you, and why you rarely want it.
- [ ] You have fetched with assoc, object, column, class and function modes.
- [ ] You can say why the query is run again for each mode in this page.

## Previous / Next

Previous: [01 PDO connection](../01-pdo-connection/README.md). Next: [03 Prepared statements](../03-prepared-statements/README.md).
