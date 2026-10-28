<?php
// A hotel booking, as it might arrive from a booking website.
$booking = [
    'guest' => 'Maria Borg',
    'guests' => '4',
    'nights' => 3,
    'pricePerNight' => 85.5,
    'breakfast' => false,
    'notes' => '',
    'promoCode' => null,
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Booking</title>
</head>

<body>
    <h1>Booking for <?= $booking['guest'] ?></h1>
</body>

</html>
