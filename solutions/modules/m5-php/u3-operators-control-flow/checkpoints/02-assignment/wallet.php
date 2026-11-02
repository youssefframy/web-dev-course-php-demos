<?php
// Wallet
$balance = 50;
printf('<p>Starting balance: %.2f</p>', $balance);

$balance -= 12.50;
printf('<p>After buying a book: %.2f</p>', $balance);

$balance += 20;
printf('<p>After a birthday gift: %.2f</p>', $balance);

$balance *= 1.05;
printf('<p>After 5%% interest: %.2f</p>', $balance);

// By value: changing the copy leaves $balance alone
$copy = $balance;
$copy = 0;
printf('<p>After emptying the copy, balance is %.2f</p>', $balance);

// By reference: $sharedBalance is another name for $balance
$sharedBalance = &$balance;
$sharedBalance -= 10;
printf('<p>After spending 10 from the shared balance, balance is %.2f</p>', $balance);
