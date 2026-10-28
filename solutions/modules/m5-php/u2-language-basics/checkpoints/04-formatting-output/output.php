<?php
/* Printing and formatting */

// print() and echo
print('<p>I was printed.</p>');
echo '<p>Show this', ' and then this', ' and then that.</p>';

// printf()
$ferry = 'Gaudos';
printf('<p>The %s ferry leaves Ċirkewwa at %s.</p>', $ferry, '10:45');

$cartTotal = 23.4567;
printf('<p>Total in your cart: €%.2f</p>', $cartTotal);

$student1 = 4;
$student2 = 32;
$student3 = 168;
printf('<p>Students: %03d, %03d, %03d</p>', $student1, $student2, $student3);

// Padding
printf('<p>[%10s]</p>', 'Hello');
echo '<pre>';
printf("[%10s]\n", 'Hello');
printf("[%-10s]\n", 'Hello');
printf("[%'*10s]\n", 'Hello');
printf("[%.2s]\n", 'Hello');
printf("[%+d] [%+d]\n", 1024, -1024);
echo '</pre>';

// String functions
$messy = '   hello from gozo   ';
echo '<pre>';
echo '[' . $messy . ']' . "\n";
echo '[' . trim($messy) . ']' . "\n";
echo strlen($messy) . ' characters, ' . strlen(trim($messy)) . " after trim\n";
echo strtoupper(trim($messy)) . "\n";
echo ucfirst(trim($messy)) . "\n";
echo ucwords(trim($messy)) . "\n";
echo strcmp('Alpha', 'Beta') . ' ' . strcmp('Beta', 'Alpha') . ' ' . strcmp('Alpha', 'Alpha') . "\n";
echo '</pre>';
