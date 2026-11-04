<!doctype html>
<html lang="en">
<?php include 'includes/head.php' ?>

<body <?= isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true' ? 'data-bs-theme="dark"' : '' ?>>
    <?php include 'includes/menu.php' ?>

    <div class="container">
        <h1>Bootstrap</h1>
        <p>
            Bootstrap is a free CSS framework for building responsive websites. It gives you ready-made styles
            for layouts, menus, cards, tables, badges and much more, so you can lay out a page with class names
            instead of writing all the CSS yourself. It was created at Twitter and first released in 2011.
        </p>
        <p>
            <small><em>Last updated: <?= date("Y-m-d H:i:s.", filemtime("aboutbootstrap.php")) ?></em></small>
        </p>
    </div>

    <?php include 'includes/footer.php' ?>
</body>

</html>
