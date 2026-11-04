<?php
// Shop helpers

function formatPrice($amount, $currency = '€')
{
    return $currency . number_format($amount, 2);
}

function average(...$numbers)
{
    $total = 0;
    foreach ($numbers as $number) {
        $total += $number;
    }
    return $total / count($numbers);
}

function applyDiscount(&$price, $percent)
{
    $price -= $price * $percent / 100;
}

$price = 80;
echo '<p>Price: ' . formatPrice($price) . '</p>';
applyDiscount($price, 25);
echo '<p>After a 25% discount: ' . formatPrice($price) . '</p>';

echo '<p>Average: ' . average(4, 8, 15, 16, 23, 42) . '</p>';
echo '<p>In dollars: ' . formatPrice(9.5, '$') . '</p>';
