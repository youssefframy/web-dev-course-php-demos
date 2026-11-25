<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\User;

$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email) {
        $token = (new User($email, ''))->createResetToken();
        if ($token !== null) {
            // No mail server in the dev container, so the "email" is a file in outbox/.
            $dir = dirname(__DIR__, 4) . '/outbox';
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $link = "http://{$_SERVER['HTTP_HOST']}/index.php?view=reset&token=$token";
            file_put_contents(
                $dir . '/reset-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.txt',
                "To: $email\nSubject: Reset your eShop password\n\nOpen this link within one hour to choose a new password:\n$link\n"
            );
        }
    }
    // The same answer whether or not the address has an account, so the page cannot be used to find out who is registered.
    $sent = true;
}
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <?php if ($sent) { ?>
            <div class="alert alert-success">If that address has an account, we have sent it a link to reset the password.</div>
        <?php } ?>

        <form method="post" action="index.php?view=forgot">
            <h1 class="h3 mb-3 fw-normal">Forgot your password?</h1>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Send me a link</button>
            <a href="index.php?view=login">Back to sign in</a>
        </form>
    </div>
</div>
