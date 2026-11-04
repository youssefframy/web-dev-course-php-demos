<?php declare(strict_types=1);
// Types, static variables and recursion

echo '<h5>Type Hinting</h5>';
function showCartTotal(array $cartItems, string $currency, bool $rightPlacement, float $freeThreshold): string
{
    $cartTotal = array_sum($cartItems);
    $formattedTotal = number_format($cartTotal, 2, '.', ',');
    $result = '<p>Your total is <strong>' . ($rightPlacement ? $formattedTotal . $currency : $currency . $formattedTotal) . '</strong></p>';
    if ($cartTotal > $freeThreshold) {
        $result .= '<p>Your cart qualifies for free delivery.</p>';
    }
    return $result;
}

echo showCartTotal([12.18, 3.45, 11.80, 12.65], '€', false, 40);
echo showCartTotal([23, 45, 67], '€', true, 40);

echo '<h5>Static Variables</h5>';
function sayHello(string $user): void
{
    static $callCount = 0;
    $callCount++;
    echo "<p>Hello, $user. Function has been called $callCount times.</p>";
}

sayHello('Bob');
sayHello('Alice');
sayHello('Terence');

echo '<h5>Recursion</h5>';
function showResults(array $results): void
{
    echo '<ul>';
    foreach ($results as $key => $value) {
        if (is_array($value)) {
            echo "<li>$key:</li>";
            showResults($value);
        } else {
            echo "<li>$key: $value</li>";
        }
    }
    echo '</ul>';
}

$results = [
    'English' => 90,
    'Maths' => [
        'Sit 1' => 61,
        'Sit 2' => 78,
    ],
    'History' => 76,
    'Chemistry' => [
        'Sit 1' => [
            'Theory' => 87,
            'Practical' => 48,
        ],
        'Sit 2' => [
            'Theory' => 90,
            'Practical' => 65,
        ],
    ],
];
showResults($results);
