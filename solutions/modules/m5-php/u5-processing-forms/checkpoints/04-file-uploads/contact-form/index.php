<?php
$email = '';
$comments = '';
$maxSize = ini_get('upload_max_filesize');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $comments = $_POST['comments'];
    printf('<p>Received email: %s and comments %s</p>', $email, $comments);

    if (isset($_POST['reason']) && count($_POST['reason']) > 0) {
        echo '<p>Reasons for contacting us:</p>';
        echo '<ul>';
        foreach ($_POST['reason'] as $reason) {
            echo "<li>$reason</li>";
        }
        echo '</ul>';
    }

    if (isset($_POST['contact_method'])) {
        printf('<p>We can contact you by %s</p>', join(', ', $_POST['contact_method']));
    }
    printf('<p>You %s want to subscribe to our newsletter</p>', $_POST['newsletter'] === 'yes' ? 'do' : 'do not');


    if (isset($_FILES['uploadedFile']) && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) {
        $uploadedFile = $_FILES['uploadedFile']['tmp_name'];

        // Check what the file really is, and pick its extension from that
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $uploadedFile);
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];
        if (!array_key_exists($mimeType, $allowedTypes)) {
            die('Invalid file format!');
        }

        // Give the file a unique name, so uploads never overwrite each other
        $uploadFolder = 'uploads/';
        $uniqueId = time() . uniqid(rand());
        $origName = pathinfo($_FILES['uploadedFile']['name'], PATHINFO_FILENAME);
        $ext = $allowedTypes[$mimeType];
        $destFile = $uploadFolder . $origName . "_$uniqueId." . $ext;

        if (move_uploaded_file($uploadedFile, $destFile)) {
            echo '<p>File uploaded successfully.</p>';
        } else {
            echo '<p>There was an error uploading the file.</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Using Forms</title>
</head>

<body>
    <h3>Contact Us</h3>
    <form enctype="multipart/form-data" name="contact_form" method="POST" action="index.php">
        <label for="email" style="display: inline-block; width: 100px;">Email</label>
        <input type="email" id="email" name="email" value="<?= $email ?>"><br><br>
        <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
        <select name="reason[]" id="reason" multiple>
            <option>Technical Support</option>
            <option>Sales</option>
            <option>Bug Report</option>
            <option>General</option>
        </select><br><br>
        <label style="display: inline-block; width: 100px;">Contact me by</label>
        <input type="checkbox" name="contact_method[]" value="email">Email
        <input type="checkbox" name="contact_method[]" value="phone">Phone
        <input type="checkbox" name="contact_method[]" value="sms">SMS
        <input type="checkbox" name="contact_method[]" value="whatsapp">WhatsApp
        <br><br>
        <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
        <input type="radio" name="newsletter" value="yes">Yes
        <input type="radio" name="newsletter" value="no" checked>No
        <br><br>
        <label for="comments" style="display: inline-block; width: 100px;">Comments</label>
        <textarea id="comments" name="comments"><?= $comments ?></textarea><br><br>
        <label for="uploadedFile" style="display: inline-block; width: 100px;">Screenshot</label>
        <input type="file" id="uploadedFile" name="uploadedFile" accept="image/png, image/jpeg, image/gif"> (Max Size: <?= $maxSize ?>)
        <br><br>
        <label style="display: inline-block; width: 100px;"></label>
        <input type="submit" value="Send">
    </form>
</body>

</html>
