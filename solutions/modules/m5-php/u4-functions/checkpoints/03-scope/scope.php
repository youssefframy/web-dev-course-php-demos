<?php
// Return values and scope

echo '<h5>Returning by Value</h5>';
$garage = ['Kia', 'BMW', 'Tesla', 'Ford', 'GM'];

function getVehicle($carNumber = 0)
{
    global $garage;
    return $garage[$carNumber];
}

echo '<p>Array before changes:</p>';
print_r($garage);
$car = getVehicle(1);
$car = 'Mercedes';
echo '<p>Array after changes:</p>';
print_r($garage);

echo '<h5>Returning by Reference</h5>';
function &getVehicleRef($carNumber = 0)
{
    global $garage;
    return $garage[$carNumber];
}

$car = &getVehicleRef(1);
$car = 'Mercedes';
echo '<p>Array after changes:</p>';
print_r($garage);

echo '<h5>Global Scope</h5>';
$userName = 'Alice';
$userSurname = 'Anderson';

function getFullName()
{
    global $userName, $userSurname;
    return $userName . ' ' . $userSurname;
}

echo '<p>Full Name: ' . getFullName() . '</p>';

echo '<h5>Local Scope</h5>';
function getCarDetails()
{
    $carMake = 'Seat';
    $carModel = 'Arona';
    $carYear = 2018;
    return sprintf('%d %s %s', $carYear, $carMake, $carModel);
}

echo '<p>It is a ' . getCarDetails() . '.</p>';

echo '<h5>Nested Functions</h5>';
function showPrices(...$prices)
{
    function showAsEuro($value)
    {
        return '€' . number_format($value, 2, '.', ',');
    }

    echo '<ul>';
    foreach ($prices as $price) {
        echo '<li>' . showAsEuro($price) . '</li>';
    }
    echo '</ul>';
}

showPrices(34.789, 212.009, 1612.881, 12.89);
