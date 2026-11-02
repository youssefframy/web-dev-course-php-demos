<?php
// Assignment Operators
echo '<h5>Assignment With Operation</h5>';
$a = 1;
printf('<p>a starts with the value %d</p>', $a);
printf('<p>a+=6: %d</p>', $a += 6);
printf('<p>a-=4: %d</p>', $a -= 4);
printf('<p>a*=2: %d</p>', $a *= 2);
printf('<p>a/=2: %d</p>', $a /= 2);
printf('<p>a%%=2: %d</p>', $a %= 2);
echo '<p>' . $a .= ' is the final result</p>';

echo '<h5>Assignment By Value</h5>';
$a = 20;
printf('<p>a starts with the value %d</p>', $a);
$b = $a;
$b = 30;
printf('<p>After changing b, a contains %d</p>', $a);

echo '<h5>Assignment By Reference</h5>';
$a = 20;
printf('<p>a starts with the value %d</p>', $a);
$b = &$a;
$b = 30;
printf('<p>After changing b, a contains %d</p>', $a);
