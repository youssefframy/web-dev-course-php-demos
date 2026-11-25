# Checkpoint 03: Prepared statements

## Goal

Send values to the database with prepared statements, in the three ways PDO offers, and run one statement several times.

## Concepts

- A prepared statement is SQL with placeholders for the values. You send the SQL first with `prepare()`, then the values with `execute()`. The database knows which part is SQL and which part is data, so a value can never change the meaning of the statement. That is what stops SQL injection. Building the SQL by putting a variable inside a string does not.
- A second benefit: the database can plan the statement once and run it many times. When prepared statements are not supported by a database, PDO imitates them.
- There are two placeholder styles, and you cannot mix them in one statement:
  - Named: `:price`. Pass an array keyed by name. The colon is optional in the array keys.
  - Question marks: `?`. Pass an array in the same order as the marks.
- `bindValue($param, $value, $type)` attaches a value now. A question-mark parameter is counted from 1.
- `bindParam($param, $variable)` attaches a **variable**, by reference. The variable is read when `execute()` runs, so you can change it and execute again. It needs a variable. A literal such as `100` gives an error.
- The optional third argument gives the type, for example `PDO::PARAM_BOOL`, `PDO::PARAM_INT` or `PDO::PARAM_STR` (the default). Without it, PDO sends `false` as an empty string, which MariaDB then reads as `0`. Say the type, so it does not depend on that.
- `bindColumn()` goes the other way: it attaches PHP variables to the columns of a result. Fetch with `PDO::FETCH_BOUND` and the variables are filled in for each row.
- Placeholders stand in for **values** only. You cannot use one for a table name, a column name or `ORDER BY`.

## Before you start

You need the database from the [unit README](../../README.md). Rename your checkpoint 02 page, then create a new `index.php` in the unit's [`pdo/`](../../pdo) folder, starting with:

```php
<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';

function show(array $rows): void
{
    foreach ($rows as $row) {
        printf('<p>%s (&euro;%.2f)</p>', htmlspecialchars($row->name), $row->price);
    }
}

try {
    $dbh = new PDO("mysql:host=$host;dbname=shoppingcart;charset=utf8mb4", 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true,
    ]);
} catch (PDOException $e) {
    exit('Database error: ' . htmlspecialchars($e->getMessage()));
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Prepared statements</title>
</head>

<body>
    <h1>Prepared statements</h1>
    <!-- the steps add sections here -->
</body>

</html>
```

## Steps

1. Follow along: named parameters, with the values passed to `execute()`. Put this PHP in the body:

   ```php
   <h2>Named parameters</h2>
   <?php
   $sth = $dbh->prepare('SELECT * FROM Product WHERE price < :price AND requiresDeposit = :deposit');
   $sth->execute(['price' => 100, 'deposit' => 0]);
   show($sth->fetchAll(PDO::FETCH_OBJ));
   ?>
   ```

   *Check: three courses, each `(€90.00)`. Not the Unity one.*

2. Follow along: the same with question marks. The values go in the order of the marks.

   ```php
   $sth = $dbh->prepare('SELECT * FROM Product WHERE price < ? AND requiresDeposit = ?');
   $sth->execute([100, 0]);
   show($sth->fetchAll(PDO::FETCH_OBJ));
   ```

   *Check: the same three courses.* Swap the two values to `[0, 100]` and reload, then change them back. Nothing matches, because the values went to the wrong marks.

3. Follow along: `bindValue()`, with the type for the boolean.

   ```php
   $sth = $dbh->prepare('SELECT * FROM Product WHERE price < :price AND requiresDeposit = :deposit');
   $sth->bindValue('price', 100);
   $sth->bindValue('deposit', false, PDO::PARAM_BOOL);
   $sth->execute();
   show($sth->fetchAll(PDO::FETCH_OBJ));
   ```

   *Check: the same three courses.*

4. Follow along: `bindParam()` and a statement run twice.

   ```php
   $sth = $dbh->prepare('SELECT * FROM Product WHERE price = :price');
   $price = 90;
   $sth->bindParam('price', $price);
   $sth->execute();
   show($sth->fetchAll(PDO::FETCH_OBJ));

   $price = 360;
   $sth->execute();
   show($sth->fetchAll(PDO::FETCH_OBJ));
   ```

   *Check: the three 90 courses, then the Unity course.* Now change `bindParam('price', $price)` to `bindParam('price', 90)`. *Check: a fatal error, `Cannot pass parameter 2 by reference`. Only variables can be bound by reference.* Change it back.

5. Follow along: `bindColumn()`. Columns can be bound by position (from 1) or by name.

   ```php
   $sth = $dbh->prepare('SELECT name, price, requiresDeposit FROM Product');
   $sth->execute();
   $sth->bindColumn(1, $name);
   $sth->bindColumn('price', $price);
   $sth->bindColumn('requiresDeposit', $requiresDeposit);
   while ($sth->fetch(PDO::FETCH_BOUND)) {
       printf('<p>%s (&euro;%.2f) Deposit: %s</p>', htmlspecialchars($name), $price, $requiresDeposit ? 'Yes' : 'No');
   }
   ```

   *Check: four lines, with `Deposit: Yes` on Unity only.*

6. Follow along: see why it matters. Pretend this came from a search box:

   ```php
   $input = "x' OR '1'='1";
   $sth = $dbh->prepare('SELECT * FROM Product WHERE name = :name');
   $sth->execute(['name' => $input]);
   printf('<p>Searching for %s found %d products.</p>', htmlspecialchars($input), count($sth->fetchAll()));
   ```

   *Check: found 0 products. The whole text is treated as a name.* Now, only to see the danger, run the unsafe version in a scratch page, never in real code:

   ```php
   $rows = $dbh->query("SELECT * FROM Product WHERE name = '$input'")->fetchAll();
   ```

   *Check: it finds all 4 products. The quote in the input ended the string, and `OR '1'='1'` became part of the SQL.* Delete the scratch line.

7. Your turn: write a prepared statement that finds products whose name contains a word typed in a `?word=` URL parameter. Read it with `filter_input(INPUT_GET, 'word')`. The value must carry the wildcards, for example `'%' . $word . '%'`, because the `%` belongs to the **value**, not to the SQL.

   *Check: `?word=CSS` finds one course, and `?word=` or a word that matches nothing shows a polite message.*

## Done when

- [ ] You have used named parameters, question marks, `bindValue`, `bindParam` and `bindColumn`.
- [ ] You can say why a prepared statement stops SQL injection.
- [ ] You can say the difference between `bindValue()` and `bindParam()`.
- [ ] You can say why a placeholder cannot stand in for a column name.

## Previous / Next

Previous: [02 Queries and fetch modes](../02-queries-fetch-modes/README.md). Next: [04 Insert, update, delete](../04-insert-update-delete/README.md).
