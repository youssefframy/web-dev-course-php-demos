<?php

/**
 * A postcard, built with a heredoc.
 *
 * @author Maria Borg <maria.borg@example.com>
 */

$town = 'Marsaxlokk';
$sender = 'Maria';
$weather = 'sunny';

$postcard = <<<CARD
    <h2>Greetings from $town</h2>
    <p>
        It is $weather in $town, and the fishing boats are out.
        $sender says: "Wish you were here!"
    </p>
    CARD;

echo $postcard;

echo '<p>Love, ' . $sender . '</p>';
echo "<p>Love, $sender</p>";
