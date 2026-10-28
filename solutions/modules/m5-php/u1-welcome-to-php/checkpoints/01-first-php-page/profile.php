<?php
    $name = "Maria Borg";
    $hometown = "Valletta";
    $favouriteFood = "Pastizzi";
    $luckyNumber = 7;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My Profile</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="card">
            <h1><?= $name ?></h1>
            <p class="role">Student, Back-End Development With PHP</p>
            <dl>
                <dt>Hometown</dt>
                <dd><?= $hometown ?></dd>
                <dt>Favourite food</dt>
                <dd><?= $favouriteFood ?></dd>
                <dt>Lucky number</dt>
                <dd><?= $luckyNumber ?></dd>
            </dl>
            <?php echo "<p class='note'>This card was built by PHP.</p>"; ?>
        </main>
    </body>
</html>
