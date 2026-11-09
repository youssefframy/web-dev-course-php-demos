<?php
// Product page, with filtered query parameters

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$sort = filter_input(INPUT_GET, 'sort') ?? 'none';

if ($id === null) {
    $idText = 'none';
} elseif ($id === false) {
    $idText = 'invalid';
} else {
    $idText = $id;
}

echo "<p>Product ID: $idText</p>";
echo '<p>Sorted by: ' . htmlspecialchars($sort) . '</p>';
