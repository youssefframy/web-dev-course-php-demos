<?php
// Comparison Operators
echo '<h5>Comparison Operators</h5>';
$a = 42;
$b = '42';
echo '<pre>';
var_dump($a == $b);
var_dump($a === $b);
var_dump($a != $b);
var_dump($a !== $b);
var_dump($a < 50, $a > 50, $a <= 42, $a >= 43);
var_dump(1 <=> 2, 2 <=> 2, 3 <=> 2);
echo '</pre>';

// Logical Operators
echo '<h5>Logical Operators</h5>';
echo '<pre>';
var_dump(true && false);
var_dump(true || false);
var_dump(true xor true);
var_dump(!true);
echo '</pre>';

// Array Operators
echo '<h5>Array Operators</h5>';
$first = ['a' => 1, 'b' => 2];
$second = ['b' => 3, 'c' => 4];
$sameButShuffled = ['b' => 2, 'a' => 1];
echo '<pre>';
print_r($first + $second);
var_dump($first == $sameButShuffled);
var_dump($first === $sameButShuffled);
echo '</pre>';

// Conditional Assignment Operators
echo '<h5>Conditional Assignment</h5>';
$a = 5;
$b = 5;
$x = $a == $b ? 'Same' : 'Different';
echo "<p>Ternary: $x</p>";

$nickname = null;
$x = $nickname ?? 'Guest';
echo "<p>Null coalescing: $x</p>";
$x = $undefinedVariable ?? 'No warning either';
echo "<p>Null coalescing: $x</p>";
