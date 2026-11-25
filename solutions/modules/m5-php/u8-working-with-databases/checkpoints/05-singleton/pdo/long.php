<?php
require 'DBConnect.php';
$conn = DBConnect::getInstance()->getConnection();

$sth = $conn->prepare('SELECT Product.name, Product.price FROM Product WHERE requiresDeposit = :deposit');
$sth->bindValue('deposit', true, PDO::PARAM_BOOL);
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_OBJ);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Our Long Courses</title>
</head>

<body>
    <h1>Our Long Courses</h1>
    <?php foreach ($result as $row) { ?>
        <p>Course: <?= htmlspecialchars($row->name) ?>, Price: &euro;<?= number_format($row->price, 2) ?></p>
    <?php } ?>
    <p><a href="index.php">Back</a></p>
</body>

</html>
