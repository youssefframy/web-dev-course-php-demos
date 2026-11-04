<?php
// Products within budget

$products = [
    ['name' => 'Notebook', 'price' => 3.5],
    ['name' => 'Backpack', 'price' => 45],
    ['name' => 'Pen', 'price' => 1.2],
    ['name' => 'Headphones', 'price' => 79.99],
];
$budget = 50;

$names = array_map(fn($product) => $product['name'], $products);
echo '<p>All products: ' . implode(', ', $names) . '</p>';

$affordable = array_filter(
    $products,
    function ($product) use ($budget) {
        return $product['price'] <= $budget;
    }
);

echo "<p>Within a budget of €$budget:</p>";
echo '<ul>';
foreach ($affordable as $product) {
    echo '<li>' . $product['name'] . ': €' . number_format($product['price'], 2) . '</li>';
}
echo '</ul>';
