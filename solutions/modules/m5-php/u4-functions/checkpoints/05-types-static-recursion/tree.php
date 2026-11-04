<?php declare(strict_types=1);
// Counting marks with recursion

$results = [
    'English' => 90,
    'Maths' => [
        'Sit 1' => 61,
        'Sit 2' => 78,
    ],
    'History' => 76,
    'Chemistry' => [
        'Sit 1' => [
            'Theory' => 87,
            'Practical' => 48,
        ],
        'Sit 2' => [
            'Theory' => 90,
            'Practical' => 65,
        ],
    ],
];

function countMarks(array $results): int
{
    $count = 0;
    foreach ($results as $value) {
        if (is_array($value)) {
            $count += countMarks($value);
        } else {
            $count++;
        }
    }
    return $count;
}

function sumMarks(array $results): int
{
    $sum = 0;
    foreach ($results as $value) {
        if (is_array($value)) {
            $sum += sumMarks($value);
        } else {
            $sum += $value;
        }
    }
    return $sum;
}

function averageMark(array $results): float
{
    return sumMarks($results) / countMarks($results);
}

printf(
    '<p>There are %d marks, with an average of %s.</p>',
    countMarks($results),
    number_format(averageMark($results), 1)
);
