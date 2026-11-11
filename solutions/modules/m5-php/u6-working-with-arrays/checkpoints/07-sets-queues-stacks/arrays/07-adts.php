<?php
echo '<pre>';

$maltaCities = ['Valletta', 'Safi', 'Rabat', 'Zebbug', 'Qormi'];
$moroccoCities = ['Casablanca', 'Rabat', 'Marrakech', 'Safi'];

echo "Set union\n";
print_r(array_unique(array_merge($maltaCities, $moroccoCities)));
echo "Set intersection\n";
print_r(array_intersect($maltaCities, $moroccoCities));
echo "Set difference\n";
print_r(array_diff($maltaCities, $moroccoCities));

// Queue: first in, first out
$names = ['Alice', 'Bob', 'Charles', 'Daniela'];
array_push($names, 'Eliott');
echo "Enqueued Eliott\n";
print_r($names);
$name = array_shift($names);
echo "Dequeued: $name\n";
print_r($names);

// Stack: last in, first out
$names = ['Alice', 'Bob', 'Charles', 'Daniela'];
array_unshift($names, 'Eliott');
echo "Pushed Eliott\n";
print_r($names);
$name = array_shift($names);
echo "Popped: $name\n";
print_r($names);
