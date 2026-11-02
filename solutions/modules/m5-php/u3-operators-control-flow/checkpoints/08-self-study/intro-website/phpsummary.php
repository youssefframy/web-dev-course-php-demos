<!doctype html>
<html lang="en">
<?php include 'includes/head.php' ?>

<body <?= isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true' ? 'data-bs-theme="dark"' : '' ?>>
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
        ?>
        <div class="card">
            <div class="card-header">
                Loaded Extensions
            </div>
            <div class="card-body">
                <?php
                foreach ($extensions as $extension) {
                    echo "<span class='badge text-bg-info' style='margin: 2px;'>$extension</span>";
                }
                ?>
            </div>
        </div>
        <div class="card" style="margin-top: 10px;">
            <div class="card-header">
                Server Environment
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($_SERVER as $key => $value) {
                            echo "<tr><td>$key</td><td>$value</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php' ?>
</body>

</html>
