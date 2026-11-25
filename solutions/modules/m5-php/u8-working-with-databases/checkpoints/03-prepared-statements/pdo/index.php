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

    <h2>Named parameters, values given to execute()</h2>
    <?php
    $sth = $dbh->prepare('SELECT * FROM Product WHERE price < :price AND requiresDeposit = :deposit');
    $sth->execute(['price' => 100, 'deposit' => 0]);
    show($sth->fetchAll(PDO::FETCH_OBJ));
    ?>

    <h2>Question marks, in order</h2>
    <?php
    $sth = $dbh->prepare('SELECT * FROM Product WHERE price < ? AND requiresDeposit = ?');
    $sth->execute([100, 0]);
    show($sth->fetchAll(PDO::FETCH_OBJ));
    ?>

    <h2>bindValue()</h2>
    <?php
    $sth = $dbh->prepare('SELECT * FROM Product WHERE price < :price AND requiresDeposit = :deposit');
    $sth->bindValue('price', 100);
    $sth->bindValue('deposit', false, PDO::PARAM_BOOL);
    $sth->execute();
    show($sth->fetchAll(PDO::FETCH_OBJ));
    ?>

    <h2>bindParam(): one statement, run twice</h2>
    <?php
    $sth = $dbh->prepare('SELECT * FROM Product WHERE price = :price');
    $price = 90;
    $sth->bindParam('price', $price); // a reference: the value is read when execute() runs
    $sth->execute();
    show($sth->fetchAll(PDO::FETCH_OBJ));

    $price = 360;
    $sth->execute();
    show($sth->fetchAll(PDO::FETCH_OBJ));
    ?>

    <h2>bindColumn()</h2>
    <?php
    $sth = $dbh->prepare('SELECT name, price, requiresDeposit FROM Product');
    $sth->execute();
    $sth->bindColumn(1, $name);
    $sth->bindColumn('price', $price);
    $sth->bindColumn('requiresDeposit', $requiresDeposit);
    while ($sth->fetch(PDO::FETCH_BOUND)) {
        printf('<p>%s (&euro;%.2f) Deposit: %s</p>', htmlspecialchars($name), $price, $requiresDeposit ? 'Yes' : 'No');
    }
    ?>

    <h2>Why not just build the string?</h2>
    <?php
    // The value could come from a form. Anything the visitor types goes into the value, never into the SQL.
    $input = "x' OR '1'='1";
    $sth = $dbh->prepare('SELECT * FROM Product WHERE name = :name');
    $sth->execute(['name' => $input]);
    printf('<p>Searching for %s found %d products.</p>', htmlspecialchars($input), count($sth->fetchAll()));
    ?>
</body>

</html>
