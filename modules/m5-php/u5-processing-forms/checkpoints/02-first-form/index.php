<?php
$email = 'jaachima@example.com';
$comments = 'Please write down your comments here';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $comments = $_POST['comments'];

  printf('<p>Received Email: %s and comments $s</p>', $email, $comments);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Using Forms for this example</title>
</head>
<body>
  <h3>Contact US</h3>

  <form name="contact_form" method="POST" action="index.php">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= $email ?>"> <br><br>
    <label for="comments">Comments</label>
    <textarea name="comments" id="comments"><?= $comments ?></textarea>
    <input type="submit" value="send">
  </form>
</body>
</html>