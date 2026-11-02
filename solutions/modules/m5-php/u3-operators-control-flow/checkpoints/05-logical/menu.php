<?php
// Set menu
$wantsStarter = true;
$wantsDessert = false;
$hasBooking = false;
$isMember = true;
$isKitchenOpen = true;

if ($hasBooking || $isMember) {
    echo '<p>Welcome, please take a seat.</p>';
} else {
    echo '<p>Sorry, members and bookings only tonight.</p>';
}

if ($wantsStarter xor $wantsDessert) {
    echo '<p>Order accepted.</p>';
} else {
    echo '<p>The set menu comes with a starter or a dessert, but not both.</p>';
}

if (!$isKitchenOpen) {
    echo '<p>The kitchen is closed.</p>';
} else {
    echo '<p>The kitchen is open.</p>';
}

if (($hasBooking || $isMember) && $isKitchenOpen) {
    echo '<p>Your order is on its way.</p>';
}
