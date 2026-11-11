<?php
echo '<pre>';

// Two dimensions: each student has a list of subjects
$gradeBook = [
    'John Jefferson' => ['English', 'Geography', 'Physics'],
    'Georgia Graham' => ['English', 'French', 'Chemistry'],
    'Harriet Hasan' => ['Biology', 'Philosophy']
];
print_r($gradeBook);
printf("Georgia's second subject is %s\n", $gradeBook['Georgia Graham'][1]);

// Three dimensions: each subject has a midterm and a final mark
$gradeBookDetail = [
    'John Jefferson' => [
        'English' => ['Midterm' => 90, 'Final' => 85],
        'Geography' => ['Midterm' => 62, 'Final' => 83],
        'Physics' => ['Midterm' => 73, 'Final' => 65]
    ],
    'Georgia Graham' => [
        'English' => ['Midterm' => 92, 'Final' => 98],
        'Geography' => ['Midterm' => 91, 'Final' => 100],
        'Physics' => ['Midterm' => 76, 'Final' => 78]
    ],
    'Harriet Hasan' => [
        'Biology' => ['Midterm' => 89, 'Final' => 81],
        'Philosophy' => ['Midterm' => 76, 'Final' => 82]
    ]
];
printf("Harriet scored %d in her Philosophy final.\n", $gradeBookDetail['Harriet Hasan']['Philosophy']['Final']);

// Your turn: John's Physics midterm, then count his subjects
printf("John scored %d in his Physics midterm.\n", $gradeBookDetail['John Jefferson']['Physics']['Midterm']);
printf("John takes %d subjects.\n", count($gradeBookDetail['John Jefferson']));
