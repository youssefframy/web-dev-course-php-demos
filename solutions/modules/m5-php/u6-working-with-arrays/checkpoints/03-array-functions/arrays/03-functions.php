<?php
echo '<pre>';

$planetArray = [
    'Mercury' => 'Rocky', 'Venus' => 'Rocky', 'Earth' => 'Rocky', 'Mars' => 'Rocky',
    'Jupiter' => 'Gas', 'Saturn' => 'Gas', 'Uranus' => 'Ice', 'Neptune' => 'Gas'
];

// list() with keys
['Mercury' => $first, 'Venus' => $second] = $planetArray;
echo "$first and $second\n";

// array_slice(): part of an array
print_r(array_slice($planetArray, 0, 4));

// array_chunk(): groups, without and with the keys
print_r(array_chunk($planetArray, 3));
print_r(array_chunk($planetArray, 3, true));

// array_keys() and array_values()
print_r(array_keys($planetArray));
print_r(array_values($planetArray));

// array_key_exists()
$body = 'Pluto';
printf("%s is %s a planet.\n", $body, array_key_exists($body, $planetArray) ? '' : 'not');

// array_splice() changes the array itself
$copy = $planetArray;
array_splice($copy, 4, count($copy), ['HD 260655 b', 'HD 260655 c']);
print_r($copy);

// extract(): keys become variables
extract($planetArray);
echo "Mars is a $Mars planet.\n";

// compact(): variables become keys
$star1 = 'Sol';
$star2 = 'Proxima Centauri';
$star3 = 'Rigil Kentaurus';
print_r(compact('star1', 'star2', 'star3'));
