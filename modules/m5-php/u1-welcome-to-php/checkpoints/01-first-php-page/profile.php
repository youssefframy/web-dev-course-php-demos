<?php
    $name = "Nigel";
    $hometown = "Malta";
    $favouriteFood = "Pasta";
    $luckyNumber = 4;
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
            <h1><?php echo $name; ?></h1>
            <p class="role">Back-End Development With PHP</p>
            <dl>
                <dt>Hometown</dt>
                <dd><?php echo $hometown; ?></dd>
                <dt>Favourite food</dt>
                <dd><?php echo $favouriteFood; ?></dd>
                <dt>Lucky number</dt>
                <dd><?php echo $luckyNumber; ?> </dd>
            </dl>
        </main>
    </body>
</html>
