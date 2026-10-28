<?php
const VAT_RATE = 0.18;

$shopName = 'the corner cafe';
$customer = '   maria borg  ';
$orderNumber = 42;

// Prices before VAT
$espressoPrice = 1.60;
$croissantPrice = 2.80;
$ftiraPrice = 4.50;

$espressoWithVat = $espressoPrice * (1 + VAT_RATE);
$croissantWithVat = $croissantPrice * (1 + VAT_RATE);
$ftiraWithVat = $ftiraPrice * (1 + VAT_RATE);
$total = $espressoWithVat + $croissantWithVat + $ftiraWithVat;

echo '<pre>';
printf("%s\n", strtoupper($shopName));
printf("Order %05d for %s\n\n", $orderNumber, ucwords(trim($customer)));
printf("%-20s%6.2f\n", 'Espresso', $espressoWithVat);
printf("%-20s%6.2f\n", 'Croissant', $croissantWithVat);
printf("%-20s%6.2f\n", 'Ftira', $ftiraWithVat);
printf("%'-26s\n", '');
printf("%-20s%6.2f\n", 'Total', $total);
echo '</pre>';
