<?php
// A scratch script to try the model from the command line: php scratch.php
// Delete it when you are done.
require 'com/icemalta/shoppingcart/model/DBConnect.php';
require 'com/icemalta/shoppingcart/model/User.php';
require 'com/icemalta/shoppingcart/model/Product.php';
require 'com/icemalta/shoppingcart/model/Cart.php';

use com\icemalta\shoppingcart\model\Cart;
use com\icemalta\shoppingcart\model\Product;
use com\icemalta\shoppingcart\model\User;

$email = 'scratch' . random_int(1000, 9999) . '@example.com';

$user = new User($email, 'secret123', 'Sam', 'Scratch');
echo 'Email available: ', var_export($user->isEmailAvailable(), true), "\n";
$user->save();
echo 'Saved user id ', $user->getId(), "\n";
echo 'Email available now: ', var_export($user->isEmailAvailable(), true), "\n";

echo 'Wrong password: ', var_export((new User($email, 'nope'))->login(), true), "\n";
$login = new User($email, 'secret123');
echo 'Right password: ', var_export($login->login(), true), ' (', $login->getFirstName(), ")\n";

$products = Product::getAll();
echo 'Products: ', count($products), ', first is ', $products[0]->getName(), "\n";

$cart = new Cart($user->getId());
$cart->upsertProduct($products[0], 2);
$cart->upsertProduct($products[2], 1);
echo 'Cart items ', $cart->getItemCount(), ', total ', $cart->getTotal(), ', cart id ', $cart->id, "\n";

$reloaded = (new Cart($user->getId()))->load();
echo 'Reloaded items ', $reloaded->getItemCount(), ', total ', $reloaded->getTotal(), "\n";

$copy = unserialize(serialize($reloaded));
echo 'Survives serialize: ', $copy->getItemCount(), " items\n";

$cart->checkout();
echo 'After checkout, a fresh load finds ', (new Cart($user->getId()))->load()->getItemCount(), " items\n";
