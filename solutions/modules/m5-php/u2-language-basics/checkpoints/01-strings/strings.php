<?php

/**
 * Practising strings and comments.
 *
 * @author Maria Borg <maria.borg@example.com>
 */

$firstName = 'Maria';
$surname = 'Borg';

echo $firstName; // A one-line comment, C style.
echo '<p>My name is ' . $firstName . '</p>'; # A one-line comment, shell style.

/* A comment over
   several lines. */
echo '<p>My surname is $surname</p>';
echo "<p>My surname is $surname</p>";

$bio = <<<BIO
    <p>
        $firstName $surname lives in Mosta. She describes herself as
        "a <strong>back-end</strong> developer in training".
    </p>
    BIO;

echo $bio;
