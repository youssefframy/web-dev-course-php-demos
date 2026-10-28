<?php
    $version = 1.1;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My PHP Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="card">
            <h1>Welcome to my PHP page!</h1>
            <p>This is a normal paragraph</p>
            <?php echo "<p>This paragraph comes from PHP</p>"; ?>
            <p>Document version <?= $version ?></p>
        </main>
    </body>
</html>
