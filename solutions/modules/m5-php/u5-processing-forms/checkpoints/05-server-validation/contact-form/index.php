<?php
$email = '';
$comments = '';
$maxSize = ini_get('upload_max_filesize');
$errors = [];
$reasons = ['Technical Support', 'Sales', 'Bug Report', 'General'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    if (isset($_POST['email']) && $_POST['email'] !== '') {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email = $_POST['email'];
        } else {
            $errors[] = 'E-mail address is invalid.';
        }
    } else {
        $errors[] = 'E-mail address is missing.';
    }

    // Validate comments
    if (isset($_POST['comments']) && $_POST['comments'] !== '') {
        $comments = $_POST['comments'];
    } else {
        $errors[] = 'Comments are missing.';
    }

    // Validate reason for contact
    if (!isset($_POST['reason']) || count($_POST['reason']) === 0) {
        $errors[] = 'Please choose at least one reason for contacting us.';
    } elseif (count(array_diff($_POST['reason'], $reasons)) > 0) {
        $errors[] = 'Please choose only reasons from the list.';
    }

    // Validate contact method
    if (!isset($_POST['contact_method']) || count($_POST['contact_method']) === 0) {
        $errors[] = 'Please specify a method by which we can get in touch.';
    }

    // Validate file size: PHP refuses a file over upload_max_filesize and reports it here
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_INI_SIZE) {
        $errors[] = "The file you uploaded is too big. Max size: $maxSize";
    }

    if (isset($_FILES['uploadedFile']) && is_uploaded_file($_FILES['uploadedFile']['tmp_name'])) {
        $uploadedFile = $_FILES['uploadedFile']['tmp_name'];

        // Validate file type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $uploadedFile);
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];
        if (!array_key_exists($mimeType, $allowedTypes)) {
            $errors[] = 'The file you uploaded is not an accepted type (accepted: PNG, JPG, GIF).';
        }

        if (count($errors) === 0) {
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

    if (count($errors) === 0) {
        printf('<p>Received email: %s and comments %s</p>', $email, $comments);
        echo '<p>Reasons for contacting us:</p>';
        echo '<ul>';
        foreach ($_POST['reason'] as $reason) {
            echo "<li>$reason</li>";
        }
        echo '</ul>';
        printf('<p>We can contact you by %s</p>', join(', ', $_POST['contact_method']));
        printf('<p>You %s want to subscribe to our newsletter</p>', $_POST['newsletter'] === 'yes' ? 'do' : 'do not');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Using Forms</title>
</head>

<body>
    <?php if (count($errors) > 0) { ?>
        <div id="error_alert" style="background-color: #e3767d; border-radius: 5px; max-width: 400px; padding: 5px;">
            <ul>
                <?php
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                ?>
            </ul>
        </div>
    <?php } ?>
    <h3>Contact Us</h3>
    <form enctype="multipart/form-data" name="contact_form" method="POST" action="index.php">
        <label for="email" style="display: inline-block; width: 100px;">Email</label>
        <input type="email" id="email" name="email" value="<?= $email ?>"><br><br>
        <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
        <select name="reason[]" id="reason" multiple>
            <option <?= isset($_POST['reason']) && in_array('Technical Support', $_POST['reason']) ? 'selected' : '' ?>>Technical Support</option>
            <option <?= isset($_POST['reason']) && in_array('Sales', $_POST['reason']) ? 'selected' : '' ?>>Sales</option>
            <option <?= isset($_POST['reason']) && in_array('Bug Report', $_POST['reason']) ? 'selected' : '' ?>>Bug Report</option>
            <option <?= isset($_POST['reason']) && in_array('General', $_POST['reason']) ? 'selected' : '' ?>>General</option>
        </select><br><br>
        <label style="display: inline-block; width: 100px;">Contact me by</label>
        <input type="checkbox" name="contact_method[]" value="email" <?= isset($_POST['contact_method']) && in_array('email', $_POST['contact_method']) ? 'checked' : '' ?>>Email
        <input type="checkbox" name="contact_method[]" value="phone" <?= isset($_POST['contact_method']) && in_array('phone', $_POST['contact_method']) ? 'checked' : '' ?>>Phone
        <input type="checkbox" name="contact_method[]" value="sms" <?= isset($_POST['contact_method']) && in_array('sms', $_POST['contact_method']) ? 'checked' : '' ?>>SMS
        <input type="checkbox" name="contact_method[]" value="whatsapp" <?= isset($_POST['contact_method']) && in_array('whatsapp', $_POST['contact_method']) ? 'checked' : '' ?>>WhatsApp
        <br><br>
        <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
        <input type="radio" name="newsletter" value="yes" <?= isset($_POST['newsletter']) && $_POST['newsletter'] === 'yes' ? 'checked' : '' ?>>Yes
        <input type="radio" name="newsletter" value="no" <?= !isset($_POST['newsletter']) || $_POST['newsletter'] === 'no' ? 'checked' : '' ?>>No
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
