<?php
echo '<pre>';

function fresh(): array
{
    return [
        'indexed' => ['Gregory', 'Alice', 'Travis', 'Paula', 'Olivia'],
        'assoc' => ['Goaler' => 'Travis', 'Attacker' => 'Paula', 'Defender' => 'Olivia', 'Midfielder' => 'Gregory', 'Wingman' => 'Alice']
    ];
}

// sort(): by value, keys reset
$n = fresh();
sort($n['indexed']);
sort($n['assoc']);
echo "sort()\n";
print_r($n);

// asort(): by value, keys kept
$n = fresh();
asort($n['indexed']);
asort($n['assoc']);
echo "asort()\n";
print_r($n);

// ksort(): by key
$n = fresh();
ksort($n['assoc']);
echo "ksort()\n";
print_r($n['assoc']);

// Descending
$n = fresh();
arsort($n['assoc']);
echo "arsort()\n";
print_r($n['assoc']);

// User-defined with <=>
$readings = [
    'Monday' => [12, 14, 16, 9],
    'Tuesday' => [13, 16, 18, 3],
    'Wednesday' => [9, 10, 8, 1]
];
uasort($readings, fn($a, $b) => max($b) <=> max($a));
echo "uasort(): hottest day first\n";
print_r($readings);

// array_multisort()
$studentNames = ['Peter', 'Alice', 'Davinia', 'Bob', 'Travis'];
$studentAges = [26, 18, 21, 37, 45];
$studentGrades = [82, 90, 92, 76, 71];
array_multisort($studentNames, SORT_ASC, $studentAges, SORT_DESC, $studentGrades, SORT_DESC);
print_r([$studentNames, $studentAges, $studentGrades]);

// Natural order
$folders = ['Module 5', 'Module 1', 'Module 12'];
sort($folders);
echo "sort()\n";
print_r($folders);
natsort($folders);
echo "natsort()\n";
print_r($folders);
