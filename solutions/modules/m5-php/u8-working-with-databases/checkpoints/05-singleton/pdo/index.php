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
    <p>Same connection object on every call: <?= var_export(DBConnect::getInstance() === DBConnect::getInstance(), true) ?></p>
</body>

</html>
