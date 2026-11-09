<?php
// Product page, with query parameters

$id = $_GET['id'] ?? 'none';
$sort = $_GET['sort'] ?? 'none';

echo "<p>Product ID: $id</p>";
echo "<p>Sorted by: $sort</p>";
