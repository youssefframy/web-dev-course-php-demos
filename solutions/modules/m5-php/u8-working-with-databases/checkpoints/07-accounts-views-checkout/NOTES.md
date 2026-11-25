# Notes: Unit 8 checkpoint 07

- **Output buffer.** The slides' views call `header()` from inside the page body, where output has already started. That gives "headers already sent". The answer's `index.php` runs the view into a buffer first, and every redirect is followed by `exit`.
- **Cart badge.** `$cartSize` is worked out after the view has run, so the badge is right on the same request that changed the cart. In Unit 7 it was one request behind.
- **Redirect.** The slide sanitises `redirect` and puts it in a `Location` header. The answer only accepts a name from a list (`products`, `cart`, `checkout`, `account`).
- **Session id.** `session_regenerate_id(true)` is called on login.
- **Email.** `FILTER_VALIDATE_EMAIL` replaces `FILTER_SANITIZE_EMAIL`, so a bad address is refused, not quietly altered. A password of fewer than 8 characters is refused on the register page. Names are trimmed.
- **Guest cart.** The slide's cart view sends a guest to login **before** reading the form, so Enrol is lost. The answer applies the form to the session cart first, then redirects. The login view then saves that cart under the account. An empty cart is not saved.
- **Signed-in cart.** The cart view calls `load()` on each request, so the database is the truth. If a guest cart and an older pending cart both exist at login, the guest cart is saved as a new cart, and `load()` picks the newest. The older pending cart stays in the database, unused. Merging them is a possible improvement.
- **Checkout.** It needs a POST, a user, and a cart with items. A reload or a direct visit says there is nothing to check out, and does not crash. The slide's `unserialize($_SESSION['cart'])` fails when there is no cart.
- **Cart view email field.** Removed, as in the slides' strike-through. The order belongs to the account.
- **Escaping.** Names and descriptions go through `htmlspecialchars()`, money through `number_format()`.
