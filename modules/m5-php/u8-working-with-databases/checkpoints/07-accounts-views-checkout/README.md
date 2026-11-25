# Checkpoint 07: Cart: accounts, views and checkout

## Goal

Let visitors register, sign in and sign out, keep each signed-in visitor's cart in the database, and make checkout record the order. You change the pages to use the model from checkpoint 06.

## Concepts

- **Users in the session.** After a good login, the page stores the user with `$_SESSION['user'] = serialize($user)`. Later pages call `unserialize()` to get the object back. Checkpoint 06's `__serialize()` leaves the password out. Reading `isset($_SESSION['user'])` is how a page asks "is anyone signed in?".
- **`serialize()` yourself.** In Unit 7 the cart was assigned to the session as an object, and PHP serialized it on its own. Now the code calls `serialize()` and `unserialize()` on purpose, so you can see where it happens.
- **The model files must be loaded before `session_start()`.** PHP needs to know the classes to rebuild the objects. You did this in Unit 7, and `DBConnect` and `User` join the list.
- **Redirect, then stop.** `header('Location: ...')` asks the browser to go somewhere else, but the script keeps running. Follow it with `exit`. A header can only be sent before any output. The slides' pages put the view inside the HTML, so a redirect would fail with "headers already sent". The answer runs the view first, into an output buffer (`ob_start()` and `ob_get_clean()`), then prints the page. As a bonus, the cart badge in the menu is then up to date, because the menu is drawn after the view has run.
- **Never trust a redirect target.** The login page takes a `redirect` value from the URL. If it went straight into `header()`, a crafted link could send a visitor anywhere. The answer only accepts a name from a short list.
- **A new session id on login.** `session_regenerate_id(true)` gives the signed-in visitor a fresh session id, so an id that someone planted before sign-in is worthless.
- **For a signed-in user, the database is the truth.** The cart view reloads the user's pending cart from the database on each request, then applies the form. A guest's cart lives in the session. At login, it is saved under the new account, or the pending cart from last time is picked up.
- **One message for a bad login.** "Incorrect email or password" does not say which was wrong, so the page cannot be used to find out which emails have accounts.

## Before you start

You need the model from checkpoint 06. If you skipped it, copy the reference answer over yours (this overwrites your `shoppingcart` folder's files with the same names, and leaves your `.env`):

   ```sh
   cp -R solutions/modules/m5-php/u8-working-with-databases/checkpoints/06-cart-database-and-model/shoppingcart/. modules/m5-php/u8-working-with-databases/shoppingcart/
   ```

You work in `modules/m5-php/u8-working-with-databases/shoppingcart`. Start the server there.

## Steps

1. Follow along: rewrite `index.php`. It loads the four model files before the session starts, has a list of views, runs the chosen view into a buffer, then prints the page. Replace everything above the `<!doctype html>` line with:

   ```php
   <?php
   // The model classes must be loaded before session_start(), so PHP can rebuild objects kept in the session.
   require 'com/icemalta/shoppingcart/model/DBConnect.php';
   require 'com/icemalta/shoppingcart/model/User.php';
   require 'com/icemalta/shoppingcart/model/Product.php';
   require 'com/icemalta/shoppingcart/model/Cart.php';
   session_start();

   // Show the correct view
   $views = ['products', 'cart', 'checkout', 'login', 'register', 'account'];
   $currentView = 'products';
   $requested = filter_input(INPUT_GET, 'view', FILTER_DEFAULT);
   if ($requested !== null && in_array($requested, $views, true)) {
       $currentView = $requested;
   }

   // Run the view first, into a buffer. A view can then redirect with header(),
   // and the menu below shows the cart as it is after the view has done its work.
   ob_start();
   include "com/icemalta/shoppingcart/view/$currentView.inc.php";
   $content = ob_get_clean();

   // Determine cart size for badge
   $cartSize = isset($_SESSION['cart']) ? unserialize($_SESSION['cart'])->getItemCount() : 0;
   ?>
   ```

   In the body, replace the two `include` lines by `include` of the menu and `<?= $content ?>`:

   ```html
   <body data-bs-theme="dark">
       <div class="container">
           <?php include("com/icemalta/shoppingcart/view/menu.inc.php"); ?>
           <?= $content ?>
       </div>
   ```

   *Check: the home page shows the four courses from the database. They have no `$_SESSION['products']` any more, which is why the products view must change next.* If you see an error about `$_SESSION['products']`, that is it.

2. Follow along: the products view reads from the database now. At the top of `view/products.inc.php`, replace the `$products = $_SESSION['products'];` line:

   ```php
   <?php
   namespace com\icemalta\shoppingcart\view;

   use com\icemalta\shoppingcart\model\Product;

   $products = Product::getAll();
   ?>
   ```

   *Check: the four courses show again.*

3. Follow along: write the login view, `view/login.inc.php`. The PHP at the top does the work. It checks the redirect, signs the user in, and sorts out the cart. The form is below it.

   ```php
   <?php
   namespace com\icemalta\shoppingcart\view;

   use com\icemalta\shoppingcart\model\Cart;
   use com\icemalta\shoppingcart\model\User;

   // Where to go after signing in. Only a view we know about is accepted.
   $redirect = filter_input(INPUT_GET, 'redirect', FILTER_DEFAULT);
   if (!in_array($redirect, ['products', 'cart', 'checkout', 'account'], true)) {
       $redirect = 'products';
   }

   $loginError = false;
   $registerSuccess = filter_input(INPUT_GET, 'registerSuccess', FILTER_VALIDATE_BOOLEAN) ?? false;

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
               <a href="index.php?view=register">No Account? Create One!</a>
           </form>
       </div>
   </div>
   ```

   *Check: <http://localhost:8000/index.php?view=login> shows the form.*

4. Follow along: write the registration view, `view/register.inc.php`.

   ```php
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
   ```

   *Check: register with your own email, a password of at least 8 characters, and a name. You land on the sign in page, with a green "Your account has been created" message. The `User` table has a new row. Register again with the same email: a red message says the account exists. Try a 3-character password: a red message about the length.*

5. Follow along: write the account view, `view/account.inc.php`.

   ```php
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
   ```

   And in `view/menu.inc.php`, remove the `<!-- ... -->` comment around the person icon, so the link to the account page shows. Change its link to `index.php?view=account`, and give it the same `active` check as the cart link.

   *Check: sign in at the login page. You land on the products. Click the person icon: your email, name and `standard` access level show. Press Sign out: back on the products, and the person icon now sends you to sign in.*

6. Follow along: refactor the cart view, `view/cart.inc.php`. Its top changes to this. After it, the HTML stays the same, except that the email field in the checkout form goes: the order is now tied to the account, not to an email typed in.

   ```php
   <?php
   namespace com\icemalta\shoppingcart\view;

   use com\icemalta\shoppingcart\model\Cart;
   use com\icemalta\shoppingcart\model\Product;

   $products = Product::getAll();
   $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
   $cart = isset($_SESSION['cart']) ? unserialize($_SESSION['cart']) : new Cart();

   if ($user !== null) {
       // For a signed-in user, the database is the truth: start from their saved cart.
       $cart->setUserId($user->getId());
       $cart->load();
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $action = filter_input(INPUT_POST, 'action');
       // FILTER_VALIDATE_INT gives an int, or false/null when the value is missing or not a whole number
       $productId = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

       switch ($action) {
           case 'upsert':
               $product = current(array_filter($products, fn($product) => $product->getId() === $productId));
               $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
               if (!is_int($qty)) {
                   $qty = 1; // no quantity sent (the Enrol button), or nonsense
               }
               if ($product !== false) { // false: no product has that id
                   $cart->upsertProduct($product, $qty);
               }
               break;
           case 'delete':
               if ($productId !== false && $productId !== null) {
                   $cart->removeProduct($productId);
               }
               break;
       }

       $_SESSION['cart'] = serialize($cart); // Update the cart stored in the session
   }

   if ($user === null) {
       // A guest can fill a cart (it is kept in the session), but must sign in to see it.
       // The login view saves the cart under their account.
       header('Location: index.php?view=login&redirect=cart');
       exit;
   }

   $showDepositWarning = count(array_filter($cart->cartItems, fn($cartItem) => $cartItem->product->requiresDeposit())) > 0;
   ?>
   ```

   Delete the whole `<div class="col-md-8">` that held the `<input type="email" ...>`, and make the `<div class="col-md-4">` next to it a `<div class="col-md-12">`.

   *Check: as a guest, press Enrol on a course: the cart badge shows 1, and the page sends you to sign in. Sign in: you land on the cart, and the course is in it. Change a quantity to 3 and press refresh: the badge shows 3 and the subtotal changes. Look at the `Cart` and `CartItem` tables in phpMyAdmin: one cart for your user, and one item with `qty` 3.*

7. Follow along: refactor the checkout view, `view/checkout.inc.php`. It no longer empties the cart in the session and stops. It saves it, marks it as checked out, and then clears the session copy.

   ```php
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
   ```

   *Check: press Checkout in the cart: `Thank you for your purchase!`, the badge goes away, and the `Cart` row has status `CHECKOUT`. Reload the page (the browser asks to send the form again): `There is nothing to check out`.*

8. Your turn: the saved cart. Add two courses, then press Sign out and close the tab. Open the site again and sign in with the same account.

   *Check: both courses are still in your cart, and the badge shows 2. The cart came back from the database.*

9. Your turn: check the redirect. Visit `index.php?view=login&redirect=https://example.com` and sign in.

   *Check: you land on the products, not on example.com.*

10. Your turn: sign in with the right email and a wrong password, and then with an email that has no account.

    *Check: the same red message both times.*

11. Your turn: two people, one browser. In a private window, register a second user and add a different course. Sign in to the first account in the normal window.

    *Check: each user sees only their own cart.*

## Done when

- [ ] Register, sign in, account page and sign out all work.
- [ ] A signed-in user's cart comes back after signing out and in.
- [ ] Checkout sets the cart's status to `CHECKOUT`, and a reload does not crash.
- [ ] An unknown `redirect` value is ignored.
- [ ] You can say why the view runs inside an output buffer.
- [ ] You can say why the login page gives one message for a wrong email and a wrong password.

## Previous / Next

Previous: [06 Cart: database and model](../06-cart-database-and-model/README.md). Next: [08 Self-study](../08-self-study/README.md).
