<?php

$cities = ['Egypt' => 'Cairo', 'Serbia' => 'belgrade', 'Malta' => 'Valletta'];

foreach ($cities as $country => $city) {
  if ($country === 'Egypt'){
    $city = 'Alex';
  }
};

// Stack -> LIFO
$names = ['Nemanja', 'Isaac', 'Nigel'];

function pop (array $example) {
  return array_shift($example);
}

array_unshift($names, 'youssef');
echo'pushed youssef to the list\n)';
print_r($names);
$name = pop($names);
printf('This is was the popped name %s', $name);