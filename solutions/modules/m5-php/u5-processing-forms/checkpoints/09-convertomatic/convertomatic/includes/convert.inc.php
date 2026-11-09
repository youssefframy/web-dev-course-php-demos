<?php
session_start();

$accuracy = 1; // Default accuracy
$errors = [];

/**
 * Checks whether a value was given in the conversion form and whether it is numeric
 * @param mixed $value the value to check
 * @return bool true if value is valid
 */
function isValidInput($value): bool
{
    return isset($value) && is_numeric($value);
}

/**
 * Convert a value from one unit to another
 * @param string $conversionType the type of conversion to carry out
 * @param float $value the value to convert
 * @param int $accuracy how many decimal places to round the result to
 * @return array an associative array containing 'fromValue', 'toValue', 'fromUnit' and 'toUnit'
 */
function convert(string $conversionType, float $value, int $accuracy): array
{
    $result = [];
    switch ($conversionType) {
        case 'c_to_f':
            $convertedValue = round(($value * 9 / 5) + 32, $accuracy);
            $fromUnit = 'C';
            $toUnit = 'F';
            break;
        case 'f_to_c':
            $convertedValue = round(($value - 32) * 5 / 9, $accuracy);
            $fromUnit = 'F';
            $toUnit = 'C';
            break;
        case 'km_to_mi':
            $convertedValue = round($value * 0.621371, $accuracy);
            $fromUnit = 'km';
            $toUnit = 'mi';
            break;
        case 'mi_to_km':
            $convertedValue = round($value / 0.621371, $accuracy);
            $fromUnit = 'mi';
            $toUnit = 'km';
            break;
        case 'cm_to_in':
            $convertedValue = round($value / 2.54, $accuracy);
            $fromUnit = 'cm';
            $toUnit = '"';
            break;
    }
    $result['fromValue'] = $value;
    $result['toValue'] = $convertedValue;
    $result['fromUnit'] = $fromUnit;
    $result['toUnit'] = $toUnit;
    return $result;
}

// Load history from cookie if current session is empty
if (isset($_COOKIE['conversionHistory']) && !isset($_SESSION['conversionHistory'])) {
    $_SESSION['conversionHistory'] = json_decode($_COOKIE['conversionHistory'], true);
    $_SESSION['accuracy'] = $_COOKIE['accuracy'] ?? $accuracy;
}
if (isset($_SESSION['accuracy'])) {
    $accuracy = (int) $_SESSION['accuracy'];
}
$keepHistory = isset($_SESSION['conversionHistory']); // If there's no history, disable keeping history by default

// Process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputValue = filter_input(INPUT_POST, 'convert_value');
    if (!isValidInput($inputValue)) {
        $errors[] = 'Invalid input value.';
    } else {
        $conversionType = filter_input(INPUT_POST, 'convert_type');
        $accuracy = (int) filter_input(INPUT_POST, 'convert_accuracy');
        $keepHistory = filter_input(INPUT_POST, 'convert_keep_history') === 'on';
        $result = convert($conversionType, $inputValue, $accuracy);

        if ($keepHistory) {
            $_SESSION['conversionHistory'][] = $result;
            // A cookie holds about 4KB, so keep only the last 10 conversions
            $_SESSION['conversionHistory'] = array_slice($_SESSION['conversionHistory'], -10);
            $_SESSION['accuracy'] = $accuracy;
            setcookie('conversionHistory', json_encode($_SESSION['conversionHistory']), time() + 604800, '/');
            setcookie('accuracy', $accuracy, time() + 604800, '/');
        } else {
            // The only way to delete a cookie is to make it expire
            unset($_SESSION['conversionHistory']);
            unset($_COOKIE['conversionHistory']);
            setcookie('conversionHistory', '', -1, '/');
            unset($_SESSION['accuracy']);
            unset($_COOKIE['accuracy']);
            setcookie('accuracy', '', -1, '/');
        }
    }
}
