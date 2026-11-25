<?php
$host = getenv('DB_HOST') ?: '127.0.0.1';

class Product
{
    public $id;
    public $name;
    public $price;
    public $description;
    public $featuredImage;
    public $requiresDeposit;
}

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

try {
    $dbh = new PDO("mysql:host=$host;dbname=shoppingcart;charset=utf8mb4", 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true,
    ]);

    // The default fetch mode, FETCH_BOTH, gives every column twice.
    $both = $dbh->query('SELECT * FROM Product')->fetchAll();
    $assoc = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_ASSOC);
    $objects = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_OBJ);
    $names = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_COLUMN, 1);
    $prices = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_COLUMN, 2);
    $products = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_CLASS, 'Product');
    $lines = $dbh->query('SELECT * FROM Product')->fetchAll(PDO::FETCH_FUNC, 'describe');
    // query() takes the fetch mode as its second argument, and a statement can be looped over directly.
    $loop = $dbh->query('SELECT * FROM Product', PDO::FETCH_OBJ);
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

    <h2>FETCH_ASSOC: one row</h2>
    <pre><?php var_dump($assoc[0]); ?></pre>

    <h2>FETCH_OBJ: one row</h2>
    <pre><?php var_dump($objects[0]); ?></pre>

    <h2>FETCH_COLUMN: names (column 1) and prices (column 2)</h2>
    <pre><?php var_dump($names, $prices); ?></pre>

    <h2>FETCH_CLASS: one row</h2>
    <pre><?php var_dump($products[0]); ?></pre>

    <h2>FETCH_FUNC</h2>
    <?php foreach ($lines as $line) { ?>
        <p><?= htmlspecialchars($line) ?></p>
    <?php } ?>

    <h2>Looping over query()</h2>
    <?php foreach ($loop as $row) { ?>
        <p><?= htmlspecialchars($row->name) ?></p>
    <?php } ?>
</body>

</html>
