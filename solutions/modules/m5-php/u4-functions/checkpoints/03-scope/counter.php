<?php
// Counters and scope

$visits = 0;

function countVisit()
{
    global $visits;
    $visits++;
}

countVisit();
countVisit();
countVisit();
echo "<p>Visits: $visits</p>";

function countVisitBy(&$counter)
{
    $counter++;
}

$clicks = 0;
countVisitBy($clicks);
countVisitBy($clicks);
echo "<p>Clicks: $clicks</p>";

function getGreeting($name)
{
    $message = "Welcome back, $name!";
    return $message;
}

echo '<p>' . getGreeting('Bob') . '</p>';
