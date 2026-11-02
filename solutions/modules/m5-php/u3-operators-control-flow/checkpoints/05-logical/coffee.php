<?php
// Logical Operators
echo '<h5>AND</h5>';
$beanWeight = 50;
$waterWeight = 200;

if ($beanWeight > 30 && $waterWeight > 150) {
    echo '<p>Coffee machine working...</p>';
} else {
    echo '<p>Error: check coffee beans and water amount.</p>';
}

echo '<h5>OR</h5>';
$grindWeight = 30;
if ($beanWeight > 30 || $grindWeight > 20) {
    echo '<p>Coffee machine working...</p>';
} else {
    echo '<p>Error: check coffee beans/grind and water amount.</p>';
}

echo '<h5>Combined</h5>';
if (($beanWeight > 30 || $grindWeight > 20) && $waterWeight > 150) {
    echo '<p>Coffee machine working...</p>';
} else {
    echo '<p>Error: check coffee beans/grind and water amount.</p>';
}

echo '<h5>XOR</h5>';
$coffeeValveState = 'Open';
$steamValveState = 'Closed';
if ($coffeeValveState === 'Open' xor $steamValveState === 'Open') {
    echo '<p>Coffee machine working...</p>';
} else {
    echo '<p>Error: please close steam valve or coffee valve.</p>';
}

echo '<h5>NOT</h5>';
$waterTemperature = 40;
if (!($waterTemperature >= 60)) {
    echo '<p>Status: Heating...</p>';
} else {
    echo '<p>Ready to make coffee.</p>';
}
