<?php
session_start();

$email = '';
$comments = '';
$inputReason = [];
$inputContactMethod = [];
$newsletter = 'no';
$maxSize = ini_get('upload_max_filesize');
$errors = [];
$reasons = ['Technical Support', 'Sales', 'Bug Report', 'General'];

// A remembered visitor with a new session: copy the cookie into the session
if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

// The hidden field 'action' tells us which form was sent
$action = filter_input(INPUT_POST, 'action');

if ($action === 'saveUser') {
    $user = filter_input(INPUT_POST, 'username');
    if (isset($user) && $user !== '') {
        $_SESSION['username'] = $user;
        if (filter_input(INPUT_POST, 'remember') === 'remember') {
            setcookie('username', $user, time() + 604800, '/');
        }
    }
} elseif ($action === 'forgetUser') {
    unset($_SESSION['username']);
    setcookie('username', '', time() - 3600, '/');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    $inputEmail = filter_input(INPUT_POST, 'email');
    if (isset($inputEmail) && $inputEmail !== '') {
        if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
            $email = $inputEmail;
        } else {
            $errors[] = 'E-mail address is invalid.';
        }
    } else {
        $errors[] = 'E-mail address is missing.';
    }

    // Validate comments
    $inputComments = filter_input(INPUT_POST, 'comments');
    if (isset($inputComments) && $inputComments !== '') {
        $comments = $inputComments;
    } else {
        $errors[] = 'Comments are missing.';
    }

    // Validate reason for contact: anything but an array becomes false
    $inputReason = filter_input(INPUT_POST, 'reason', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if (!is_array($inputReason) || count($inputReason) === 0) {
        $inputReason = [];
        $errors[] = 'Please choose at least one reason for contacting us.';
    } elseif (count(array_diff($inputReason, $reasons)) > 0) {
        $errors[] = 'Please choose only reasons from the list.';
    }

    // Validate contact method
    $inputContactMethod = filter_input(INPUT_POST, 'contact_method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if (!is_array($inputContactMethod) || count($inputContactMethod) === 0) {
        $inputContactMethod = [];
        $errors[] = 'Please specify a method by which we can get in touch.';
    }

    // Newsletter: anything but 'yes' counts as no
    $newsletter = filter_input(INPUT_POST, 'newsletter') === 'yes' ? 'yes' : 'no';

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
        // Everything the visitor typed is escaped before it goes into the page
        printf('<p>Received email: %s and comments %s</p>', htmlspecialchars($email), htmlspecialchars($comments));
        echo '<p>Reasons for contacting us:</p>';
        echo '<ul>';
        foreach ($inputReason as $reason) {
            echo '<li>' . htmlspecialchars($reason) . '</li>';
        }
        echo '</ul>';
        printf('<p>We can contact you by %s</p>', htmlspecialchars(join(', ', $inputContactMethod)));
        printf('<p>You %s want to subscribe to our newsletter</p>', $newsletter === 'yes' ? 'do' : 'do not');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Using Forms</title>
    <style>
        .validation_message {
            font-size: small;
            font-style: italic;
            color: red;
            margin: 0;
            display: none;
        }

        .validation_invalid {
            outline: 1px solid red;
        }
    </style>
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
    <form enctype="multipart/form-data" name="contact_form" method="POST" action="index.php" id="contactForm" novalidate>
        <label for="email" style="display: inline-block; width: 100px;">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <p class="validation_message">Please enter a valid email address.</p>
        <br>
        <label for="reason" style="display: inline-block; width: 100px;">Query Type</label>
        <select name="reason[]" id="reason" multiple>
            <option <?= in_array('Technical Support', $inputReason) ? 'selected' : '' ?>>Technical Support</option>
            <option <?= in_array('Sales', $inputReason) ? 'selected' : '' ?>>Sales</option>
            <option <?= in_array('Bug Report', $inputReason) ? 'selected' : '' ?>>Bug Report</option>
            <option <?= in_array('General', $inputReason) ? 'selected' : '' ?>>General</option>
        </select>
        <p class="validation_message">Please choose one or more reasons for your query.</p>
        <br>
        <label style="display: inline-block; width: 100px;">Contact me by</label>
        <input type="checkbox" name="contact_method[]" value="email" <?= in_array('email', $inputContactMethod) ? 'checked' : '' ?>>Email
        <input type="checkbox" name="contact_method[]" value="phone" <?= in_array('phone', $inputContactMethod) ? 'checked' : '' ?>>Phone
        <input type="checkbox" name="contact_method[]" value="sms" <?= in_array('sms', $inputContactMethod) ? 'checked' : '' ?>>SMS
        <input type="checkbox" name="contact_method[]" value="whatsapp" <?= in_array('whatsapp', $inputContactMethod) ? 'checked' : '' ?>>WhatsApp
        <p class="validation_message">Please tell us how you'd like us to get back to you.</p>
        <br>
        <label style="display: inline-block; width: 100px;">Subscribe to Newsletter</label>
        <input type="radio" name="newsletter" value="yes" <?= $newsletter === 'yes' ? 'checked' : '' ?>>Yes
        <input type="radio" name="newsletter" value="no" <?= $newsletter === 'no' ? 'checked' : '' ?>>No
        <br><br>
        <label for="comments" style="display: inline-block; width: 100px;">Comments</label>
        <textarea id="comments" name="comments"><?= htmlspecialchars($comments) ?></textarea>
        <p class="validation_message">Please enter your query.</p>
        <br>
        <label for="uploadedFile" style="display: inline-block; width: 100px;">Screenshot</label>
        <input type="file" id="uploadedFile" name="uploadedFile" accept="image/png, image/jpeg, image/gif"> (Max Size: <?= $maxSize ?>)
        <br><br>
        <label style="display: inline-block; width: 100px;"></label>
        <input type="submit" value="Send">
    </form>

    <?php if (isset($_SESSION['username'])) { ?>
        <h3>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></h3>
        <form name="forgetForm" method="post" action="index.php">
            <input type="hidden" name="action" value="forgetUser">
            <input type="submit" value="Forget me">
        </form>
    <?php } else { ?>
        <form name="welcomeForm" method="post" action="index.php" style="margin-top: 20px;">
            <label for="username" style="display: inline-block; width: 100px;">Username</label>
            <input type="text" id="username" name="username" required><br><br>
            <label style="display: inline-block; width: 100px;"></label>
            <input type="checkbox" name="remember" value="remember">Remember me<br><br>
            <label style="display: inline-block; width: 100px;"></label>
            <input type="hidden" name="action" value="saveUser">
            <input type="submit" value="Save">
        </form>
    <?php } ?>

    <script>
        document.getElementById('contactForm').addEventListener('submit', validateForm);

        function validateForm(evt) {
            let containsErrors = false;

            // Reset validation from the last attempt
            for (const message of document.querySelectorAll('.validation_message')) {
                message.style.display = 'none';
            }
            for (const field of document.querySelectorAll('.validation_invalid')) {
                field.classList.remove('validation_invalid');
            }

            // Email
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const email = document.getElementById('email');
            if (!re.test(email.value)) {
                containsErrors = true;
                showError(email);
            }

            // Query Type
            const reason = document.getElementById('reason');
            if (reason.selectedOptions.length === 0) {
                containsErrors = true;
                showError(reason);
            }

            // Contact Method
            const checkboxes = document.querySelectorAll('input[name="contact_method[]"]');
            const checkedOne = Array.from(checkboxes).some(checkbox => checkbox.checked);
            if (!checkedOne) {
                containsErrors = true;
                for (const checkbox of checkboxes) {
                    checkbox.classList.add('validation_invalid');
                }
                getNextSibling(checkboxes[0], '.validation_message').style.display = 'block';
            }

            // Comments
            const comments = document.getElementById('comments');
            if (comments.value.trim() === '') {
                containsErrors = true;
                showError(comments);
            }

            // Stop the form from being sent if anything is wrong
            if (containsErrors) {
                evt.preventDefault();
            }
        }

        // Outline a field in red, and show the message that follows it
        function showError(field) {
            field.classList.add('validation_invalid');
            getNextSibling(field, '.validation_message').style.display = 'block';
        }

        // Find the first element after elem that matches selector
        function getNextSibling(elem, selector) {
            let sibling = elem.nextElementSibling;
            while (sibling) {
                if (sibling.matches(selector)) {
                    return sibling;
                }
                sibling = sibling.nextElementSibling;
            }
            return null;
        }
    </script>
</body>

</html>
