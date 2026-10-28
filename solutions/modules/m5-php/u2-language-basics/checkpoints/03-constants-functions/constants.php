<?php
/* Constants and functions */

// Constants
define('VAT_RATE', 0.18);
echo '<p>VAT in Malta is charged at ' . VAT_RATE . '</p>';

define('SIZES', ['Small', 'Medium', 'Large']);
echo '<p>Cup sizes:</p>';
print_r(SIZES);

const CAFE_NAME = 'Kafè Kordin';
echo '<h1>' . CAFE_NAME . '</h1>';

// Functions
function getGreeting()
{
    return 'Merħba!';
}

echo '<p>' . getGreeting() . '</p>';

// Variable functions
$greet = 'getGreeting';
echo "<p>{$greet()} Welcome to our cafe.</p>";
