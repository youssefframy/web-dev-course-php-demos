<?php
/* Fun with data types */

// Scalar types
$anInt = 42;
echo "<p>The value $anInt is an " . gettype($anInt) . '</p>';
$aFloat = 3.5;
echo "<p>The value $aFloat is a " . gettype($aFloat) . '</p>';
$town = 'Mosta';
echo "<p>The value $town is a " . gettype($town) . '</p>';
$isRaining = false;
echo "<p>The value $isRaining is a " . gettype($isRaining) . '</p>';
$isSunny = true;
echo "<p>The value $isSunny is a " . gettype($isSunny) . '</p>';

// Compound types
$temperatures = [23.1, 22.9, 16.8, 14.3];
echo '<p>Temperatures:</p>';
print_r($temperatures);

$company = ['CEO' => 'Alice Abela', 'CTO' => 'Bob Bonnici'];
echo '<p>Company:</p>';
print_r($company);

class Ferry
{
    public $name = '';
}

$boat = new Ferry();
$boat->name = 'Gaudos';
echo '<p>Ferry:</p>';
print_r($boat);

// Type coercion
$val1 = 42;
$val2 = "89";
$val3 = $val1 + $val2;
echo "<p>Type coercion: $val3</p>";

// Type casting
$val4 = "29";
$val5 = 42.82;
$val6 = (int) $val4 + (int) $val5;
echo "<p>Type casting: $val6</p>";
