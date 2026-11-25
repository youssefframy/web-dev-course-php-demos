# Notes: Unit 8 self-study

The reference answer includes the extra credit. Without it, a student only needs the admin page.

## Admin page

- The view checks for a signed-in user, then for the `admin` level, at the top, before anything else runs. The login page's redirect list includes `admin`.
- The access level in the session is the level at login. A user promoted afterwards must sign in again. The README tells the student to.
- Every POST carries a `csrf` token from `$_SESSION['csrf']`, checked with `hash_equals()`.
- The image must match `^[A-Za-z0-9_.-]+$`. A name such as `../x` is refused. A student may use `basename()` instead.
- `getById()` uses `FETCH_NUM` and the constructor, so no second mapping is needed.
- Deleting a product that is in a cart raises SQLSTATE `23000` (the foreign key from `CartItem`). The answer catches it. `PDOException::getCode()` is a **string**, so the comparison is with `'23000'`.
- Editing a product's price changes the price a customer sees in a cart they already had. The `price` stored in `CartItem` is only a record. See the notes for checkpoint 06.

## Forgot password

- Needs one more table, `PasswordReset`, in `password_reset.sql`. The student writes their own. It is not in the seed, so checkpoint 06 and 07 have no unused table.
- The token is `bin2hex(random_bytes(32))`. The database holds `sha256` of it, with an expiry and a used flag.
- The page answers the same whether or not the email exists.
- `User::resetPassword()` selects the token row with `FOR UPDATE` inside a transaction, sets the password, and marks the token used. Two requests with the same link cannot both win.
- The "email" is a file in `outbox/`, as in Unit 7. The link uses `$_SERVER['HTTP_HOST']`, which a visitor can set, so a real site would take its address from configuration. It is written to a file only.
- A reset does not sign out other sessions. A real site would also do that.
