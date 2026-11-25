<?php
namespace com\icemalta\shoppingcart\view;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sign out. The cart is already saved in the database, so nothing is lost.
    unset($_SESSION['user']);
    session_destroy();
    header('Location: index.php?view=products');
    exit;
} elseif (!isset($_SESSION['user'])) {
    header('Location: index.php?view=login&redirect=account');
    exit;
}

$user = unserialize($_SESSION['user']);
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Account</h5>
            </div>
        </div>
    </div>

    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Email</th>
                            <th scope="col">Name</th>
                            <th scope="col">Surname</th>
                            <th scope="col">Access Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                            <td><?= htmlspecialchars($user->getFirstName() ?? '') ?></td>
                            <td><?= htmlspecialchars($user->getLastName() ?? '') ?></td>
                            <td><?= htmlspecialchars($user->getAccessLevel()) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="index.php?view=account">
                    <button type="submit" class="btn btn-danger"><i class="bi-box-arrow-right"></i> Sign out</button>
                </form>
            </div>
        </div>
    </div>
</div>
