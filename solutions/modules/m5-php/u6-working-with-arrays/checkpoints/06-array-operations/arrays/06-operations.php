<?php
echo '<pre>';

$fileSizes = [124, 67, 1045, 23];
printf("Total file size: %dKb\n", array_sum($fileSizes));

$maltaCities = ['Valletta', 'Safi', 'Rabat', 'Zebbug', 'Qormi'];
$moroccoCities = ['Casablanca', 'Rabat', 'Marrakech', 'Safi'];

echo "array_merge()\n";
print_r(array_merge($maltaCities, $moroccoCities));

echo "array_diff()\n";
print_r(array_diff($maltaCities, $moroccoCities));

$emails = ['keith@icemalta.com', 'bob@gmail.com', 'alice@icemalta.com', 'travis@outlook.com'];

echo "array_filter(): keep the internal addresses\n";
print_r(array_filter($emails, fn($email) => str_contains($email, '@icemalta.com')));

// Keys are kept. array_values() renumbers them
print_r(array_values(array_filter($emails, fn($email) => str_contains($email, '@icemalta.com'))));
