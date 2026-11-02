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

        echo '<h2>Your request</h2>';
        printf('<p>Method: %s</p>', $_SERVER['REQUEST_METHOD']);
        printf('<p>Address: %s</p>', $_SERVER['REQUEST_URI']);
        printf('<p>Browser: %s</p>', $_SERVER['HTTP_USER_AGENT']);
        printf('<p>Time: %s</p>', date('d/m/Y H:i', $_SERVER['REQUEST_TIME']));

        echo '<h2>Loaded extensions</h2>';
        echo '<pre>';
        var_dump($extensions);
        echo '</pre>';

        echo '<h2>Server environment</h2>';
        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';

        echo '<h2>Global variables</h2>';
        echo '<pre>';
        print_r($GLOBALS);
        echo '</pre>';

        echo '<h2>Current file</h2>';
        printf('<p>Full path: %s</p>', __FILE__);
        printf('<p>Name: %s</p>', basename(__FILE__));
        ?>
    </div>

    <?php include 'includes/footer.php' ?>
</body>

</html>
