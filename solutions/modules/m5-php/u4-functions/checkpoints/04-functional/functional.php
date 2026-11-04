<?php
// Functions as values

echo '<h5>Variable Functions</h5>';
function myFunc1()
{
    echo '<p>Hello! This is myFunc1()</p>';
}

function myFunc2($arg)
{
    echo "<p>Hello! This is myFunc2() with argument $arg</p>";
}

$myVar1 = 'myFunc1';
$myVar1();
$myVar2 = 'myFunc2';
$myVar2(1234);

$myVar3 = 'bogusFunction';
if (function_exists($myVar3)) {
    $myVar3();
} else {
    echo '<p>There is no function with that name!</p>';
}

echo '<h5>Anonymous Functions</h5>';
$greeting = function ($name) {
    return "Hello, $name";
};
printf('<p>%s</p>', $greeting('Bob'));

$otherGreeting = fn($name) => "Hello again, $name";
printf('<p>%s</p>', $otherGreeting('Bob'));

echo '<h5>Callbacks</h5>';
$productPrices = [34.789, 212.009, 1612.881, 12.89];
$result = array_map(fn($price) => '€' . number_format($price, 2, '.', ','), $productPrices);
print_r($result);

echo '<h5>Closures</h5>';
$cartTotals = [34.789, 212.009, 1612.881, 12.89];
$freeThreshold = 40;
$processedCart = array_map(
    function ($price) use ($freeThreshold) {
        $delivery = '';
        if ($price > $freeThreshold) {
            $delivery = 'Free delivery!';
        }
        return '€' . number_format($price, 2, '.', ',') . " $delivery";
    },
    $cartTotals
);
print_r($processedCart);
