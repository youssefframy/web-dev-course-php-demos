<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\Cart;
use com\icemalta\shoppingcart\model\User;

// Where to go after signing in. Only a view we know about is accepted.
$redirect = filter_input(INPUT_GET, 'redirect', FILTER_DEFAULT);
if (!in_array($redirect, ['products', 'cart', 'checkout', 'account', 'admin'], true)) {
    $redirect = 'products';
}

$loginError = false;
$registerSuccess = filter_input(INPUT_GET, 'registerSuccess', FILTER_VALIDATE_BOOLEAN) ?? false;
$passwordReset = filter_input(INPUT_GET, 'passwordReset', FILTER_VALIDATE_BOOLEAN) ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    $user = $email && $password ? new User($email, $password) : null;
    if ($user !== null && $user->login()) {
        session_regenerate_id(true); // a new session id for a new sign-in
        $_SESSION['user'] = serialize($user);

        if (isset($_SESSION['cart'])) {
            // The visitor filled a cart before signing in. If it has items, save it under their account.
            $cart = unserialize($_SESSION['cart']);
            if ($cart->id === 0 && $cart->getItemCount() > 0) {
                $cart->setUserId($user->getId());
                $cart->save();
                $_SESSION['cart'] = serialize($cart);
            }
        } else {
            // Otherwise, pick up the cart they left pending last time.
            $cart = (new Cart($user->getId()))->load();
            if ($cart->getItemCount() > 0) {
                $_SESSION['cart'] = serialize($cart);
            }
        }

        header("Location: index.php?view=$redirect");
        exit;
    }
    $loginError = true;
}
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <?php if ($registerSuccess) { ?>
            <div class="alert alert-success">Your account has been created, you can now login.</div>
        <?php } ?>
        <?php if ($passwordReset) { ?>
            <div class="alert alert-success">Your password has been changed, you can now login.</div>
        <?php } ?>
        <?php if ($loginError) { ?>
            <div class="alert alert-danger">Incorrect email or password.</div>
        <?php } ?>

        <form method="post" action="index.php?view=login&amp;redirect=<?= urlencode($redirect) ?>">
            <h1 class="h3 mb-3 fw-normal">Please sign in.</h1>

            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
            <a href="index.php?view=register">No Account? Create One!</a><br>
            <a href="index.php?view=forgot">Forgot your password?</a>
        </form>
    </div>
</div>
