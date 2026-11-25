<?php
namespace com\icemalta\shoppingcart\view;

use com\icemalta\shoppingcart\model\Product;
use PDOException;

// Only administrators may be here.
if (!isset($_SESSION['user'])) {
    header('Location: index.php?view=login&redirect=admin');
    exit;
}
if (unserialize($_SESSION['user'])->getAccessLevel() !== 'admin') {
    header('Location: index.php?view=products');
    exit;
}

// A token that only this session knows, sent back with every form, so another site cannot post to this page for us.
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(16));

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = filter_input(INPUT_POST, 'csrf', FILTER_DEFAULT) ?? '';
    $action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!hash_equals($_SESSION['csrf'], $token)) {
        $error = 'Your session expired. Please try again.';
    } elseif ($action === 'delete' && is_int($id)) {
        $product = Product::getById($id);
        if ($product === null) {
            $error = 'That product does not exist.';
        } else {
            try {
                $product->delete();
                $message = 'Product deleted.';
            } catch (PDOException $e) {
                // 23000: a foreign key refuses, because the product is in a cart
                $error = $e->getCode() === '23000'
                    ? 'That product is in a customer\'s cart, so it cannot be deleted.'
                    : 'The product could not be deleted.';
            }
        }
    } elseif ($action === 'save') {
        $name = trim((string) filter_input(INPUT_POST, 'name', FILTER_DEFAULT));
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $description = trim((string) filter_input(INPUT_POST, 'description', FILTER_DEFAULT));
        $image = trim((string) filter_input(INPUT_POST, 'featuredImage', FILTER_DEFAULT));
        $requiresDeposit = filter_input(INPUT_POST, 'requiresDeposit', FILTER_VALIDATE_BOOLEAN);

        if ($name === '' || $description === '') {
            $error = 'Name and description are required.';
        } elseif ($price === false || $price === null || $price < 0) {
            $error = 'The price must be a number, zero or more.';
        } elseif ($image !== '' && !preg_match('/^[A-Za-z0-9_.-]+$/', $image)) {
            $error = 'The image must be a file name such as masterclass_react.png.';
        } else {
            $image = $image === '' ? null : $image;
            if (is_int($id)) {
                $product = Product::getById($id);
                if ($product === null) {
                    $error = 'That product does not exist.';
                } else {
                    $product->setName($name);
                    $product->setPrice($price);
                    $product->setDescription($description);
                    $product->setFeaturedImage($image);
                    $product->setRequiresDeposit($requiresDeposit);
                    $product->save();
                    $message = 'Product updated.';
                }
            } else {
                (new Product(null, $name, $price, $description, $image, $requiresDeposit))->save();
                $message = 'Product added.';
            }
        }
    }
}

// Editing? Fill the form with that product.
$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
$editing = is_int($editId) ? Product::getById($editId) : null;
$products = Product::getAll();
?>

<div class="grid gap-0 row-gap-3">
    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Administration</h5>
            </div>
        </div>
    </div>

    <?php if ($message !== null) { ?>
        <div class="p-2 g-col-12"><div class="alert alert-success"><?= htmlspecialchars($message) ?></div></div>
    <?php } ?>
    <?php if ($error !== null) { ?>
        <div class="p-2 g-col-12"><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div></div>
    <?php } ?>

    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Deposit</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td><?= htmlspecialchars($product->getName()) ?></td>
                                <td>&euro; <?= number_format($product->getPrice(), 2) ?></td>
                                <td><?= $product->requiresDeposit() ? 'Yes' : 'No' ?></td>
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-secondary" href="index.php?view=admin&amp;edit=<?= $product->getId() ?>"><i class="bi-pencil-fill"></i></a>
                                    <form method="post" action="index.php?view=admin"
                                        onsubmit="return confirm('Delete this product?');">
                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $product->getId() ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi-trash3-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="p-2 g-col-12">
        <div class="card">
            <div class="card-header"><?= $editing ? 'Edit product' : 'Add a product' ?></div>
            <div class="card-body">
                <form method="post" action="index.php?view=admin">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                    <input type="hidden" name="action" value="save">
                    <?php if ($editing) { ?>
                        <input type="hidden" name="id" value="<?= $editing->getId() ?>">
                    <?php } ?>
                    <div class="mb-2">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" required
                            value="<?= htmlspecialchars($editing?->getName() ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="price">Price</label>
                        <input class="form-control" id="price" name="price" type="number" step="0.01" min="0" required
                            value="<?= $editing ? htmlspecialchars((string) $editing->getPrice()) : '' ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($editing?->getDescription() ?? '') ?></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="featuredImage">Image file (in the assets folder)</label>
                        <input class="form-control" id="featuredImage" name="featuredImage"
                            value="<?= htmlspecialchars($editing?->getFeaturedImage() ?? '') ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="requiresDeposit" name="requiresDeposit" value="1"
                            <?= $editing && $editing->requiresDeposit() ? 'checked' : '' ?>>
                        <label class="form-check-label" for="requiresDeposit">Requires a deposit</label>
                    </div>
                    <button class="btn btn-success" type="submit"><?= $editing ? 'Save changes' : 'Add product' ?></button>
                    <?php if ($editing) { ?>
                        <a class="btn btn-secondary" href="index.php?view=admin">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>
