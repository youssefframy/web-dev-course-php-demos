<?php
$email = '';
$comments = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $comments = $_POST['comments'];
    printf('<p>Received email: %s and comments %s</p>', $email, $comments);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Using Forms</title>
</head>

<body>
    <h3>Contact Us</h3>
    <form name="contact_form" method="POST" action="index.php">
        <label for="email" style="display: inline-block; width: 100px;">Email</label>
        <input type="email" id="email" name="email" value="<?= $email ?>"><br><br>
        <label for="comments" style="display: inline-block; width: 100px;">Comments</label>
        <textarea id="comments" name="comments"><?= $comments ?></textarea><br>
        <label style="display: inline-block; width: 100px;"></label>
        <input type="submit" value="Send">
    </form>
</body>

</html>
