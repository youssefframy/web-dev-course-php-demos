<?php
// Control Flow
echo '<h5>IF Statement</h5>';
$age = 23;
if ($age >= 18) {
    echo '<p>You can drive a car.</p>';
} else {
    echo '<p>You cannot legally drive a car yet.</p>';
}

echo '<h5>Multiple Outcomes</h5>';
$pizzaChoice = 2;
if ($pizzaChoice === 1) {
    echo '<p>Pizza Margherita</p>';
} elseif ($pizzaChoice === 2) {
    echo '<p>Pizza Pepperoni</p>';
} elseif ($pizzaChoice === 3) {
    echo '<p>Pizza Mushroom</p>';
} else {
    echo '<p>You did not choose an available pizza!</p>';
}

echo '<h5>Alternative Syntax</h5>';
if ($pizzaChoice === 1):
    echo '<p>Pizza Margherita</p>';
elseif ($pizzaChoice === 2):
    echo '<p>Pizza Pepperoni</p>';
elseif ($pizzaChoice === 3):
    echo '<p>Pizza Mushroom</p>';
else:
    echo '<p>You did not choose an available pizza!</p>';
endif;

echo '<h5>Ternary</h5>';
$isRaining = true;
$message = $isRaining ? 'Better take an umbrella!' : 'Enjoy the day out!';
echo "<p>$message</p>";

echo '<h5>Boolean Values</h5>';
$message = null;
if ($isRaining) {
    $message = 'Better take an umbrella!';
} else {
    $message = 'Enjoy the day out!';
}
echo "<p>$message</p>";

// Switch statement
echo '<h5>Switch</h5>';
$pizzaChoice = 2;
switch ($pizzaChoice) {
    case 1:
        echo '<p>Pizza Margherita</p>';
        break;
    case 2:
        echo '<p>Pizza Pepperoni</p>';
        break;
    case 3:
        echo '<p>Pizza Mushroom</p>';
        break;
    default:
        echo '<p>You did not choose an available pizza!</p>';
}

echo '<h5>Switch Fall-Through</h5>';
$logError = false;
$logWarning = false;
$logNotice = false;
$logInfo = false;
$logDebug = false;

$logLevel = 'NOTICE';
switch ($logLevel) {
    case 'DEBUG':
        $logDebug = true;
    case 'INFO':
        $logInfo = true;
    case 'NOTICE':
        $logNotice = true;
    case 'WARN':
        $logWarning = true;
    case 'ERR':
        $logError = true;
    default:
        showLogs($logError, $logWarning, $logNotice, $logInfo, $logDebug);
}

function showLogs($logError = false, $logWarning = false, $logNotice = false, $logInfo = false, $logDebug = false)
{
    // Critical, Alert and Emergency are always logged
    echo '<p>Logging: CRITICAL, ALERT, EMERGENCY</p>';
    echo '<ul>';
    echo '<li>ERR: ' . ($logError ? 'on' : 'off') . '</li>';
    echo '<li>WARN: ' . ($logWarning ? 'on' : 'off') . '</li>';
    echo '<li>NOTICE: ' . ($logNotice ? 'on' : 'off') . '</li>';
    echo '<li>INFO: ' . ($logInfo ? 'on' : 'off') . '</li>';
    echo '<li>DEBUG: ' . ($logDebug ? 'on' : 'off') . '</li>';
    echo '</ul>';
}
