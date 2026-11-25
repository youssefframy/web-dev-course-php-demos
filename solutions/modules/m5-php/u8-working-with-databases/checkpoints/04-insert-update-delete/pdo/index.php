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

    // UPDATE
    $sth = $dbh->prepare('UPDATE Product SET price = :price WHERE id = :id');
    $sth->execute(['price' => 95, 'id' => $newId]);
    $updated = $sth->rowCount();

    // DELETE: only the row this page made, so reloading never touches the real products
    $sth = $dbh->prepare('DELETE FROM Product WHERE id = :id');
    $sth->execute(['id' => $newId]);
    $deleted = $sth->rowCount();
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
    <p><?= $updated ?> rows updated in the database.</p>
    <p><?= $deleted ?> rows deleted from the database.</p>
</body>

</html>
