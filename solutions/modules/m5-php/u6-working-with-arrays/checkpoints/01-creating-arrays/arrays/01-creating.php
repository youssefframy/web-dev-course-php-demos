<?php
echo '<pre>';

// Indexed array: PHP numbers the values from 0
$cities = ['London', 'Paris', 'New York', 'Valletta', 'Bangkok'];
print_r($cities);

// Long form: identical result
$longCities = array('London', 'Paris', 'New York', 'Valletta', 'Bangkok');
print_r($longCities);
var_dump($cities === $longCities);

// Associative array: strings as keys
$ninjaCoders = ['CEO' => 'Alice Anderson', 'CTO' => 'Bob Barker', 'SVP Sales' => 'Craig Coleman'];
print_r($ninjaCoders);

// A value added without a key gets the largest numeric key plus one
$ninjaCoders = ['CEO' => 'Alice Anderson', 'CTO' => 'Bob Barker', 'SVP Sales' => 'Craig Coleman', 20 => 'Desiree Diamont', 'Eliott Earhart'];
print_r($ninjaCoders);

// Reading values
printf("The second city is %s\n", $cities[1]);
printf("The CTO of the company is %s\n", $ninjaCoders['CTO']);
$level = 'SVP';
printf("The employee is %s.\n", $ninjaCoders["$level Sales"]);

// 1 and '1' are the same key
$same = [1 => 'one'];
$same['1'] = 'uno';
print_r($same);

// Building an array one item at a time
$sports[] = 'Football';
$sports['USA'] = 'Soccer';
$sports[2] = 'Basketball';
print_r($sports);

// range()
$thirties = range(30, 39);
$fiveLetters = range('A', 'E');
var_dump($thirties, $fiveLetters);

// count() and array_pad()
printf("The size of the \$thirties array is %d\n", count($thirties));
$padded = array_pad($sports, 6, 'Some other sport');
print_r($padded);
$paddedFront = array_pad($sports, -6, 'Some other sport');
print_r($paddedFront);
printf("\$sports still has %d items\n", count($sports));
