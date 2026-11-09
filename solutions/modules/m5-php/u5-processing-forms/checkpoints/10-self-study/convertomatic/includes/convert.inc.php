<?php
session_start();

$accuracy = 1; // Default accuracy
$errors = [];

// Every unit the app knows, and every conversion it can do between them
$units = [
    'c' => 'Celsius',
    'f' => 'Fahrenheit',
    'km' => 'Kilometres',
    'mi' => 'Miles',
    'cm' => 'Centimetres',
    'in' => 'Inches',
    'eur' => 'Euro',
    'gbp' => 'Pounds sterling',
];
$conversions = ['c_to_f', 'f_to_c', 'km_to_mi', 'mi_to_km', 'cm_to_in', 'in_to_cm', 'eur_to_gbp', 'gbp_to_eur'];

// The units chosen last time, so the drop-downs are sticky
$from = 'c';
$to = 'f';

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
        case 'in_to_cm':
            $convertedValue = round($value * 2.54, $accuracy);
            $fromUnit = '"';
            $toUnit = 'cm';
            break;
        case 'eur_to_gbp':
            $convertedValue = round($value * 0.85, $accuracy);
            $fromUnit = ' EUR';
            $toUnit = ' GBP';
            break;
        case 'gbp_to_eur':
            $convertedValue = round($value / 0.85, $accuracy);
            $fromUnit = ' GBP';
            $toUnit = ' EUR';
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
    }

    // The conversion must be one we support, whatever the browser sent
    $from = (string) filter_input(INPUT_POST, 'convert_from');
    $to = (string) filter_input(INPUT_POST, 'convert_to');
    $conversionType = "{$from}_to_{$to}";
    if (!in_array($conversionType, $conversions, true)) {
        $errors[] = 'Invalid conversion type.';
    }

    // FILTER_VALIDATE_INT returns false unless the value is a whole number from 0 to 5
    $inputAccuracy = filter_input(
        INPUT_POST,
        'convert_accuracy',
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0, 'max_range' => 5]]
    );
    if ($inputAccuracy === false || $inputAccuracy === null) {
        $errors[] = 'Accuracy must be a whole number from 0 to 5.';
    }

    if (count($errors) === 0) {
        $accuracy = $inputAccuracy;
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
