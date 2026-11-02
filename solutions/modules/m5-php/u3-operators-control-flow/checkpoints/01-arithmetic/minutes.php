<?php
// Minutes to hours
$totalMinutes = 135;
$hours = (int) ($totalMinutes / 60);
$minutes = $totalMinutes % 60;
printf('<p>%d minutes is %d hours and %d minutes</p>', $totalMinutes, $hours, $minutes);

// Powers of two
printf('<p>2 ** 10 = %d</p>', 2 ** 10);

// Visitor counter
$visitors = 0;
$visitors++;
$visitors++;
$visitors++;
printf('<p>%d visitors so far</p>', $visitors);
$visitors--;
printf('<p>One left. %d visitors now</p>', $visitors);
