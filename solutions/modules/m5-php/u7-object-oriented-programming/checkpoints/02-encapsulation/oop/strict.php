<?php
// Aside: strict_types. Must be the very first statement in the file.
// Keep this experiment out of index.php (see the checkpoint README).
declare(strict_types=1);

function half(float $n): float
{
    return $n / 2;
}

echo half(5), "\n";      // 2.5: an int is allowed to widen to float
try {
    echo half('5'), "\n"; // TypeError in strict mode; without strict_types it would print 2.5
} catch (TypeError $e) {
    echo 'TypeError: ', $e->getMessage(), "\n";
}
