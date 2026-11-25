# Checkpoint 04: Insert, update, delete

## Goal

Add, change and remove a row with prepared statements, and check that each one worked.

## Concepts

- `INSERT`, `UPDATE` and `DELETE` run exactly like a `SELECT`: `prepare()`, bind or pass values, `execute()`. There is just nothing to fetch.
- `$sth->rowCount()` is the number of rows the statement changed. This is how you check it worked. Careful: on MariaDB, an `UPDATE` that sets a value to what it already is changes no row, so `rowCount()` is 0.
- `$dbh->lastInsertId()` is the `AUTO_INCREMENT` id MariaDB gave to the last row you inserted on this connection. It is a string, so cast it with `(int)`.
- MariaDB stores a boolean as the number 0 or 1. Pass `0` or `1`, or pass a PHP `true`/`false` with `bindValue(..., PDO::PARAM_BOOL)`.
- Always put a `WHERE` on `UPDATE` and `DELETE`. Without one they change every row. Use the primary key, so you affect the row you mean and no other.
- A foreign key can refuse a delete or an insert, for example deleting a product that is in a cart. PDO throws a `PDOException` with the code `23000`. Checkpoint 08 handles one.

## Before you start

You need the database from the [unit README](../../README.md). Rename your checkpoint 03 page, then create a new `index.php` in the unit's [`pdo/`](../../pdo) folder. The slides delete rows with `id >= 5`, which would delete real data if run twice. We will only ever delete the row that this page makes.

## Steps

1. Follow along: insert a product, and keep the new id. Start `index.php` with:

   ```php
   <?php
   $host = getenv('DB_HOST') ?: '127.0.0.1';

   try {
       $dbh = new PDO("mysql:host=$host;dbname=shoppingcart;charset=utf8mb4", 'root', 'root', [
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_PERSISTENT => true,
       ]);

       // INSERT
       $sth = $dbh->prepare('INSERT INTO Product(name, price, description, featuredImage, requiresDeposit)
           VALUES (:name, :price, :description, :featuredImage, :requiresDeposit)');
       $sth->bindValue('name', 'My New Product');
       $sth->bindValue('price', 100);
       $sth->bindValue('description', 'This is some new product');
       $sth->bindValue('featuredImage', 'some_image.png');
       $sth->bindValue('requiresDeposit', false, PDO::PARAM_BOOL);
       $sth->execute();
       $inserted = $sth->rowCount();
       $newId = (int) $dbh->lastInsertId();
   } catch (PDOException $e) {
       exit('Database error: ' . htmlspecialchars($e->getMessage()));
   }
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>Insert, update, delete</title>
   </head>

   <body>
       <h1>Insert, update, delete</h1>
       <p><?= $inserted ?> rows inserted into the database (new id <?= $newId ?>).</p>
   </body>

   </html>
   ```

   *Check: `1 rows inserted into the database (new id 5).` Reload: the id goes up each time, because each reload inserts another row. Check phpMyAdmin: the extra products are there.* Clean up with `./sql reset shoppingcart` before moving on.

2. Follow along: update the row you just made. Add inside the `try`, after the insert:

   ```php
   // UPDATE
   $sth = $dbh->prepare('UPDATE Product SET price = :price WHERE id = :id');
   $sth->execute(['price' => 95, 'id' => $newId]);
   $updated = $sth->rowCount();
   ```

   and show `<?= $updated ?> rows updated in the database.` *Check: 1.*

3. Follow along: delete it. After the update:

   ```php
   // DELETE
   $sth = $dbh->prepare('DELETE FROM Product WHERE id = :id');
   $sth->execute(['id' => $newId]);
   $deleted = $sth->rowCount();
   ```

   and show it. *Check: 1 inserted, 1 updated, 1 deleted. Reload as often as you like: the table is the same each time, because the page cleans up after itself.*

4. Follow along: see `rowCount()` say 0. Run the update again with the price `95`, **before** the delete, a second time (copy the three lines, and show the second count as `$updatedAgain`).

   *Check: 0. The value was already 95, so no row changed.* Remove the copy.

5. Your turn: delete a row that does not exist (`id` `9999`). *Check: no error, and `rowCount()` is 0.* This is how you know a delete found nothing.

6. Your turn: try to insert a product with a `NULL` name (`bindValue('name', null)`). *Check: a `PDOException`, `Column 'name' cannot be null`, shown by the `catch`.* Put the name back.

## Done when

- [ ] You can insert, update and delete with prepared statements, and show how many rows each changed.
- [ ] You can say what `lastInsertId()` returns, and its type.
- [ ] You can say why an `UPDATE` and a `DELETE` need a `WHERE`.
- [ ] The page leaves the `Product` table as it found it.

## Previous / Next

Previous: [03 Prepared statements](../03-prepared-statements/README.md). Next: [05 The Singleton pattern](../05-singleton/README.md).
