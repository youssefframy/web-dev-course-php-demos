<?php
// In the dev container DB_HOST is "db". On your own machine the server is on 127.0.0.1.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$dsn = "mysql:host=$host;dbname=shoppingcart;charset=utf8mb4";

$dbh = null;
$error = null;

try {
    $dbh = new PDO($dsn, 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // already the default in PHP 8, but say it
        PDO::ATTR_PERSISTENT => true,                // keep the connection for the next request
    ]);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PDO connection</title>
</head>

<body>
    <h1>PDO connection</h1>
    <?php if ($dbh !== null) { ?>
        <p>Connected to MariaDB <?= htmlspecialchars($dbh->getAttribute(PDO::ATTR_SERVER_VERSION)) ?>.</p>
    <?php } else { ?>
        <p>Could not connect: <?= htmlspecialchars($error) ?></p>
    <?php } ?>
</body>

</html>
