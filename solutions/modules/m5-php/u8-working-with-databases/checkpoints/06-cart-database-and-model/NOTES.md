# Notes: Unit 8 checkpoint 06

The answer is the Unit 7 cart, with the model moved onto the database. Differences from the slides:

- **`Product::$db` type.** The slide declares `private DBConnect $db` and then, in `__unserialize()`, assigns `DBConnect::getInstance()->getConnection()` to it. That is a `PDO`, so it is a `TypeError`. The answer keeps a `DBConnect` in every class, and always asks `getInstance()`.
- **`Product` id.** The slide types `$id` as `int` and checks `=== null` in `save()`. The answer uses `?int`, so a new product can have no id.
- **`.env` location.** The slide reads `$_SERVER['DOCUMENT_ROOT'] . '/.env'`, which is empty on the command line and wrong if the app is not served from its root. The answer uses `dirname(__DIR__, 4)`, so `scratch.php` works too. A missing file gives a clear message.
- **Credentials.** `DB_HOST` and `DB_NAME` are in `.env` too, so the same code runs in and out of the dev container.
- **`Cart::save()`** returns the number of rows it changed in total. The slide returns only the last statement's count. It also uses a transaction, so a failure cannot leave a half-saved cart, and it refuses to save a cart with no user.
- **`Cart::load()`** puts the items straight into the cart. The slide calls `upsertProduct()` for each, which saves the cart each time, and so deletes and inserts every row on every load. The answer also empties the cart first, and sets `id` to 0 when there is no pending cart.
- **Saving only for owners.** `upsertProduct()` and `removeProduct()` save only when the cart has a user. A guest cart is session-only, which the login view relies on.
- **`User::save()`** hashes the password only when there is one. A user rebuilt from the session has an empty password, and saving them used to hash the empty string over the real password. `login()` clears the password from the object once it has been checked.
- **Serialization.** `__serialize()` returns only what is needed. `Cart` serializes its items (which hold `Product`s), so `Product` needs the two methods too.
- **Prices.** The `CartItem` table stores the price at the time the cart was saved. `load()` shows the **current** product price, because it rebuilds the items from `Product`. The stored price is a record of what was in the cart, not what the page uses.
- **`scratch.php`** is a student aid. It stays in the answer so the checkpoint can be checked.
