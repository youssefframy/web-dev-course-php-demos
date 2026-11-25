<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\User;

// The token arrives in the link (GET), and comes back in a hidden field when the form is posted.
$token = filter_input($_SERVER['REQUEST_METHOD'] === 'POST' ? INPUT_POST : INPUT_GET, 'token', FILTER_DEFAULT) ?? '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
    if ($password === null || strlen($password) < 8) {
        $error = 'Your password must be at least 8 characters long.';
    } elseif (User::resetPassword($token, $password)) {
        header('Location: index.php?view=login&passwordReset=true');
        exit;
    } else {
        $token = ''; // the token was wrong, used or expired
    }
}
$valid = $token !== '' && User::isResetTokenValid($token);
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <?php if (!$valid) { ?>
            <div class="alert alert-danger">This link is not valid any more. <a href="index.php?view=forgot">Ask for a new one.</a></div>
        <?php } else { ?>
            <?php if ($error !== null) { ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php } ?>
            <form method="post" action="index.php?view=reset">
                <h1 class="h3 mb-3 fw-normal">Choose a new password.</h1>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-floating mb-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8" required>
                    <label for="password">New password</label>
                </div>
                <button class="btn btn-primary w-100 py-2" type="submit">Change password</button>
            </form>
        <?php } ?>
    </div>
</div>
