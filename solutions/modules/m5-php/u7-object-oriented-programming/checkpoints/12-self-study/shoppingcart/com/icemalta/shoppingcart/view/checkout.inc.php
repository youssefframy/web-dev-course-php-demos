<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\Cart;

$cart = $_SESSION['cart'] ?? new Cart();
$order = null;      // the summary, when there is an order to show
$emailSent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($cart->cartItems) > 0) {
    // FILTER_VALIDATE_EMAIL also refuses line breaks, which stops header injection in mail()
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    // Take a snapshot of the order before the cart is emptied
    $order = (object) [
        'email' => $email === false || $email === null ? null : $email,
        'items' => $cart->cartItems,
        'total' => $cart->getTotal(),
        'deposit' => count(array_filter($cart->cartItems, fn($i) => $i->product->requiresDeposit())) > 0,
    ];
    $_SESSION['cart'] = new Cart();

    if ($order->email !== null) {
        $lines = ["Thank you for your order!", ""];
        foreach ($order->items as $item) {
            $lines[] = sprintf('%d x %s = EUR %s', $item->qty, $item->product->getName(), number_format($item->getSubtotal(), 2));
        }
        $lines[] = '';
        $lines[] = 'Total: EUR ' . number_format($order->total, 2);
        if ($order->deposit) {
            $lines[] = 'One or more items require a deposit. We will be in touch.';
        }
        $body = implode("\r\n", $lines);

        // mail() needs a mail server, which the dev container does not have, so it may return false.
        // Either way, keep a copy in outbox/ so you can see what would have been sent.
        $emailSent = @mail($order->email, 'Your eShop order', $body, 'From: noreply@eshop.example');
        $outbox = dirname(__DIR__, 4) . '/outbox';
        if (!is_dir($outbox)) {
            mkdir($outbox, 0755, true);
        }
        file_put_contents($outbox . '/order-' . date('Ymd-His') . '.txt', "To: {$order->email}\r\n\r\n$body\r\n");
    }
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

    <?php if ($order === null) { ?>
        <div class="p-2 g-col-12">
            <div class="alert alert-info">There is no order to show. Add something to your cart first.</div>
        </div>
    <?php } else { ?>
        <div class="p-2 g-col-12">
            <div class="alert alert-success">Thank you for your purchase!</div>
            <?php if ($order->email === null) { ?>
                <div class="alert alert-warning">That email address was not valid, so no confirmation was sent.</div>
            <?php } elseif (!$emailSent) { ?>
                <div class="alert alert-warning">We could not send a confirmation email to <?= htmlspecialchars($order->email) ?>.</div>
            <?php } else { ?>
                <div class="alert alert-secondary">A confirmation was sent to <?= htmlspecialchars($order->email) ?>.</div>
            <?php } ?>
            <?php if ($order->deposit) { ?>
                <div class="alert alert-warning">One or more of the items in your order require a deposit.</div>
            <?php } ?>
        </div>
        <div class="p-2 g-col-12">
            <div class="card">
                <div class="card-header">Order summary</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th scope="col">Product</th><th scope="col">Qty</th><th scope="col">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->items as $item) { ?>
                                <tr>
                                    <th scope="row"><?= htmlspecialchars($item->product->getName()) ?></th>
                                    <td><?= $item->qty ?></td>
                                    <td>&euro; <?= number_format($item->getSubtotal(), 2) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr><th colspan="2">Total</th><th>&euro; <?= number_format($order->total, 2) ?></th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
