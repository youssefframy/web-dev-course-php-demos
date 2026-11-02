<?php
// Arithmetic Operators
$a = 21;
$b = 2;
$intDiv = 8 / 2;

echo '<h5>Arithmetic Operators</h5>';
printf('<p>%d + %d = %d</p>', $a, $b, $a + $b);
printf('<p>%d - %d = %d</p>', $a, $b, $a - $b);
printf('<p>%d * %d = %d</p>', $a, $b, $a * $b);
printf('<p>%d / %d = %.1f</p>', $a, $b, $a / $b);
printf('<p>%d %% %d = %.1f</p>', $a, $b, $a % $b);
printf('<p>%d ** %d = %d</p>', $a, $b, $a ** $b);
echo ("8 / 2 = $intDiv, which is a " . gettype($intDiv));

// Identity and negation
$price = '4.50';
echo '<h5>Identity & Negation</h5>';
echo '<p>+$price is a ' . gettype(+$price) . '</p>';
echo '<p>-$a is ' . -$a . '</p>';

// Increment & Decrement
echo '<h5>Increment & Decrement</h5>';
$a = 0;
$b = 0;
printf('<p>a is %d. After ++a, a is %d</p>', $a, ++$a);
printf('<p>b is %d. After b++, b is %d</p>', $b, $b++);
printf('<p>b is now %d</p>', $b);
