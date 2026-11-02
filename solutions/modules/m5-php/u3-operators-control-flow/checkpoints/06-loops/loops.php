<?php
// Iteration
echo '<h5>while</h5>';
$fishingPermitExpires = 31;
$currentDay = 25;
while ($currentDay <= $fishingPermitExpires) {
    echo "<p>Fishing on day $currentDay</p>";
    $currentDay++;
}

echo '<h5>do...while</h5>';
$fishingPermitExpires = 31;
$currentDay = 25;
do {
    echo "<p>Fishing on day $currentDay</p>";
    $currentDay++;
} while ($currentDay <= $fishingPermitExpires);

echo '<h5>for</h5>';
$fishingPermitExpires = 31;
for ($currentDay = 25; $currentDay <= $fishingPermitExpires; $currentDay++) {
    echo "<p>Fishing on day $currentDay</p>";
}

// For-each loop
echo '<h5>foreach</h5>';
$students = ['Alice', 'Bob', 'Craig', 'Daniela', 'Elisa'];
foreach ($students as $id => $name) {
    echo "<p>Student $id: $name</p>";
}

echo '<h5>break and continue</h5>';
$bucketCapacity = 100;
$bucketWeight = 0;
$numHouses = 20;
$spookyHouses = [2, 5, 9, 18];
for ($currentHouse = 1; $currentHouse <= $numHouses; $currentHouse++) {
    if (in_array($currentHouse, $spookyHouses)) {
        echo "<p>House $currentHouse is too spooky!</p>";
        continue;
    }
    echo "<p>House $currentHouse. Trick or Treat!</p>";
    $bucketWeight += 15;
    if ($bucketWeight > $bucketCapacity - 15) {
        echo '<p>Bucket too full, returning home.</p>';
        break;
    }
}
