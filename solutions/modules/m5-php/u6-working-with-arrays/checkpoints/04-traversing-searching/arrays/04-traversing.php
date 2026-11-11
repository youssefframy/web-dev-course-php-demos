<?php
echo '<pre>';

$planetArray = [
    'Mercury' => 'Rocky', 'Venus' => 'Rocky', 'Earth' => 'Rocky', 'Mars' => 'Rocky',
    'Jupiter' => 'Gas', 'Saturn' => 'Gas', 'Uranus' => 'Ice', 'Neptune' => 'Gas'
];

// foreach works on a copy
foreach ($planetArray as $name => $type) {
    if ($name === 'Uranus') {
        $type = 'Ice Giant';
    }
    printf("%s is a %s planet.\n", $name, $type);
}
echo $planetArray['Uranus'], "\n"; // still Ice

// foreach with & changes the original
foreach ($planetArray as $name => &$type) {
    if ($name === 'Uranus') {
        $type = 'Ice Giant';
    }
}
unset($type); // break the reference, or the next loop overwrites Neptune
echo $planetArray['Uranus'], "\n";

// for loop
$jupiterMoons = ['Europa', 'Io', 'Ganymede', 'Callisto'];
for ($i = 0; $i < count($jupiterMoons); $i++) {
    $jupiterMoons[$i] .= ' Moon';
}
print_r($jupiterMoons);

// Iterator
$it = (new ArrayObject($planetArray))->getIterator();
foreach ($it as $name => $type) {
    printf("%s is a %s planet.\n", $name, $type);
}

// array_walk()
array_walk($planetArray, fn($value, $key) => printf("The next planet is %s (%s)\n", $key, $value));

// Searching
$moons = ['Europa', 'Io', 'Ganymede', 'Callisto'];
var_dump(in_array('Callisto', $moons));
$result = array_search('Europa', $moons);
if ($result !== false) {
    echo "Europa was found at key $result.\n";
} else {
    echo "Europa was not found.\n";
}
