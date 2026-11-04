<?php
// Functions

echo '<h5>A First Function</h5>';
function add($num1, $num2)
{
    return $num1 + $num2;
}

$result = add(5, 7);
echo "<p>The result is $result</p>";

echo '<h5>By Value</h5>';
function add10($num)
{
    $num += 10;
}

$a = 4;
echo "<p>Value before function call: $a</p>";
add10($a);
echo "<p>Value after function call: $a</p>";

echo '<h5>By Reference</h5>';
function add20(&$num)
{
    $num += 20;
}

$b = 4;
echo "<p>Value before function call: $b</p>";
add20($b);
echo "<p>Value after function call: $b</p>";

echo '<h5>Default Parameters</h5>';
function raiseToPower($num, $power = 2)
{
    return pow($num, $power);
}

printf('<p>Optional argument is specified: %d</p>', raiseToPower(10, 5));
printf('<p>Optional argument is not specified: %d</p>', raiseToPower(10));

echo '<h5>Variable Parameters</h5>';
function sum()
{
    $total = 0;
    foreach (func_get_args() as $num) {
        $total += $num;
    }
    return $total;
}

printf('<p>Adding numbers together: %d</p>', sum(10, 87, 42, 56));

function sumVariadic(...$nums)
{
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}

printf('<p>Adding numbers together: %d</p>', sumVariadic(10, 87, 42, 56));
