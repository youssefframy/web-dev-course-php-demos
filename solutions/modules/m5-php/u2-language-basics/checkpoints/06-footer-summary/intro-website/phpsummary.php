<!doctype html>
<html lang="en">
<?php include 'includes/head.php' ?>

<body>
    <?php include 'includes/menu.php' ?>

    <div class="container">
        <h1>PHP Summary</h1>
        <?php
        printf('<p>You are running PHP %s.</p>', phpversion());

        $extensions = get_loaded_extensions();
        printf('<p>%d extensions are loaded.</p>', count($extensions));
        echo '<p>pdo_mysql loaded: ' . (extension_loaded('pdo_mysql') ? 'yes' : 'no') . '</p>';

        echo '<h2>Loaded extensions</h2>';
        echo '<pre>';
        var_dump($extensions);
        echo '</pre>';

        echo '<h2>Server environment</h2>';
        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';
        ?>
    </div>

    <?php include 'includes/footer.php' ?>
</body>

</html>
