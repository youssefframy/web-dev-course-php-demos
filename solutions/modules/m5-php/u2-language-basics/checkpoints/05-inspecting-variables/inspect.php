<?php
/* Inspecting variables */

$sample = ['Joe', 12, 11.23, true, new stdClass()];

echo '<pre>';
print_r($sample);
var_dump($sample);
var_export($sample);
echo '</pre>';

// Variable existence
$firstVar = 42;
$secondVar = null;
$fourthVar = 0;

echo '<h2>isset()</h2>';
echo '<p>First: ' . (isset($firstVar) ? 'set' : 'not set') . '</p>';
echo '<p>Second: ' . (isset($secondVar) ? 'set' : 'not set') . '</p>';
echo '<p>Third: ' . (isset($thirdVar) ? 'set' : 'not set') . '</p>';
echo '<p>Fourth: ' . (isset($fourthVar) ? 'set' : 'not set') . '</p>';

echo '<h2>empty()</h2>';
echo '<p>First: ' . (empty($firstVar) ? 'empty' : 'not empty') . '</p>';
echo '<p>Second: ' . (empty($secondVar) ? 'empty' : 'not empty') . '</p>';
echo '<p>Third: ' . (empty($thirdVar) ? 'empty' : 'not empty') . '</p>';
echo '<p>Fourth: ' . (empty($fourthVar) ? 'empty' : 'not empty') . '</p>';
