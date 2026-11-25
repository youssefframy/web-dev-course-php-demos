<?php
namespace com\icemalta\shoppingcart\view;

if (!isset($_SESSION['user'])) {
    header('Location: index.php?view=login&redirect=checkout');
    exit;
}

$user = unserialize($_SESSION['user']);
$cart = isset($_SESSION['cart']) ? unserialize($_SESSION['cart']) : null;

// Only check out a cart that has something in it. A reload of this page, or a visit
// without a cart, finds nothing to do.
$checkedOut = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cart !== null && $cart->getItemCount() > 0) {
    $cart->setUserId($user->getId());
    $cart->save();
    $cart->checkout();
    unset($_SESSION['cart']);
    $checkedOut = true;
}
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Checkout</h5>
            </div>
        </div>
    </div>

    <div class="p-2 g-col-12">
        <?php if ($checkedOut) { ?>
            <div class="alert alert-success">
                Thank you for your purchase!
            </div>
        <?php } else { ?>
            <div class="alert alert-info">
                There is nothing to check out. <a href="index.php">Browse our courses</a>.
            </div>
        <?php } ?>
    </div>
</div>
