<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\User;

$registerError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
    $firstName = trim((string) filter_input(INPUT_POST, 'firstName', FILTER_DEFAULT));
    $lastName = trim((string) filter_input(INPUT_POST, 'lastName', FILTER_DEFAULT));

    if (!$email) {
        $registerError = 'Please enter a valid email address.';
    } elseif ($password === null || strlen($password) < 8) {
        $registerError = 'Your password must be at least 8 characters long.';
    } else {
        $user = new User($email, $password);
        if ($user->isEmailAvailable()) {
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->save();
            header('Location: index.php?view=login&registerSuccess=true');
            exit;
        }
        $registerError = 'An account with that email address already exists.';
    }
}
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <?php if ($registerError !== null) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
        <?php } ?>

        <form method="post" action="index.php?view=register">
            <h1 class="h3 mb-3 fw-normal">Create an account.</h1>

            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First name" required>
                <label for="firstName">First name</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Surname" required>
                <label for="lastName">Surname</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Register</button>
            <a href="index.php?view=login">Already have an account? Sign in.</a>
        </form>
    </div>
</div>
