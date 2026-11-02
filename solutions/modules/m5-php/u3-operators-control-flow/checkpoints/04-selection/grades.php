<?php
// Grade
$mark = 72;
if ($mark >= 80) {
    $grade = 'A';
} elseif ($mark >= 65) {
    $grade = 'B';
} elseif ($mark >= 50) {
    $grade = 'C';
} else {
    $grade = 'Fail';
}
echo "<p>A mark of $mark gets grade $grade.</p>";

// Pass or fail, with a ternary
$result = $mark >= 50 ? 'passed' : 'failed';
echo "<p>You $result.</p>";

// Opening hours, with switch fall-through
$day = 'Saturday';
switch ($day) {
    case 'Saturday':
    case 'Sunday':
        echo '<p>Weekend: open 10:00 to 14:00.</p>';
        break;
    case 'Friday':
        echo '<p>Friday: open 08:00 to 20:00.</p>';
        break;
    default:
        echo '<p>Weekday: open 08:00 to 18:00.</p>';
}
