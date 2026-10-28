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

    <pre><?php var_dump($booking); ?></pre>

    <p><code>guests</code> looks like a number, but it is stored as a string.</p>
    <pre><?php var_dump((int) $booking['guests']); ?></pre>

    <table>
        <tr>
            <th>Key</th>
            <th>isset()</th>
            <th>empty()</th>
        </tr>
        <tr>
            <td>notes</td>
            <td><?= isset($booking['notes']) ? 'set' : 'not set' ?></td>
            <td><?= empty($booking['notes']) ? 'empty' : 'not empty' ?></td>
        </tr>
        <tr>
            <td>promoCode</td>
            <td><?= isset($booking['promoCode']) ? 'set' : 'not set' ?></td>
            <td><?= empty($booking['promoCode']) ? 'empty' : 'not empty' ?></td>
        </tr>
        <tr>
            <td>breakfast</td>
            <td><?= isset($booking['breakfast']) ? 'set' : 'not set' ?></td>
            <td><?= empty($booking['breakfast']) ? 'empty' : 'not empty' ?></td>
        </tr>
        <tr>
            <td>nights</td>
            <td><?= isset($booking['nights']) ? 'set' : 'not set' ?></td>
            <td><?= empty($booking['nights']) ? 'empty' : 'not empty' ?></td>
        </tr>
        <tr>
            <td>roomNumber</td>
            <td><?= isset($booking['roomNumber']) ? 'set' : 'not set' ?></td>
            <td><?= empty($booking['roomNumber']) ? 'empty' : 'not empty' ?></td>
        </tr>
    </table>

    <pre><?php var_export($booking); ?></pre>
</body>

</html>
