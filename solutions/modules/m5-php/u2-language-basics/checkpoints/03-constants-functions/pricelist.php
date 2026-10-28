<?php
const CAFE_NAME = 'Kafè Kordin';
const OPENING_YEAR = 2009;
define('VAT_RATE', 0.18);

function getOpeningHours()
{
    return '07:00 to 19:00';
}

$espressoPrice = 1.60;
$croissantPrice = 2.80;

$espressoWithVat = $espressoPrice * (1 + VAT_RATE);
$croissantWithVat = $croissantPrice * (1 + VAT_RATE);

echo '<h1>' . CAFE_NAME . '</h1>';
echo '<p>Open since ' . OPENING_YEAR . ', ' . getOpeningHours() . '</p>';
echo "<p>Espresso: €$espressoWithVat</p>";
echo "<p>Croissant: €$croissantWithVat</p>";
