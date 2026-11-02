<?php
// Times table
echo '<h5>7 times table</h5>';
for ($i = 1; $i <= 10; $i++) {
    printf('<p>%d x 7 = %d</p>', $i, $i * 7);
}

// Shopping
echo '<h5>Shopping</h5>';
$budget = 10.00;
$spent = 0;
$basket = [
    'Bread' => 1.20,
    'Milk' => 0.95,
    'Ġbejniet' => 3.50,
    'Coffee' => 6.00,
    'Chocolate' => 2.40,
    'Apples' => 1.80,
];
$outOfStock = ['Milk'];

foreach ($basket as $item => $price) {
    if (in_array($item, $outOfStock)) {
        echo "<p>$item is out of stock.</p>";
        continue;
    }
    if ($spent + $price > $budget) {
        printf('<p>%s costs %.2f, which is over budget. Stopping.</p>', $item, $price);
        break;
    }
    $spent += $price;
    printf('<p>Bought %s for %.2f</p>', $item, $price);
}
printf('<p>Spent %.2f of %.2f</p>', $spent, $budget);

// Countdown
echo '<h5>Countdown</h5>';
$count = 5;
while ($count > 0) {
    echo "<p>$count</p>";
    $count--;
}
echo '<p>Lift off!</p>';
