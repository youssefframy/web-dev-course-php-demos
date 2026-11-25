# Checkpoint 08: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to add an administration page to the shopping cart. The extra credit adds a forgot password flow.

## Concepts

Nothing new, except for the two ideas below. You need:

- `Product` and `User` from checkpoint 06, and the views from checkpoint 07
- prepared statements, `rowCount()` and `lastInsertId()` from this unit
- `filter_input()` from Unit 5
- **Access levels.** A signed-in user has an `accessLevel`. Hiding a link is not security: the page itself must check the level and refuse anyone else. Do the check at the top of the view, as the account view does for "signed in".
- **A forged form.** A page that changes data can be tricked into doing it by another website, which makes the visitor's browser post to it (CSRF). The usual defence is a secret value, kept in the session and sent back in a hidden field with every form, which the page checks before it acts. `hash_equals()` compares two strings without leaking where they differ.

## Before you start

You need the shopping cart from checkpoint 07. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u8-working-with-databases/checkpoints/07-accounts-views-checkout/shoppingcart/. modules/m5-php/u8-working-with-databases/shoppingcart/
```

Start the server in `modules/m5-php/u8-working-with-databases/shoppingcart`.

## Steps

1. Make an administrator. Register a new user in the app, then change their access level to `admin` in phpMyAdmin, or with `./sql shell shoppingcart`:

   ```sql
   UPDATE User SET accessLevel = 'admin' WHERE email = 'you@example.com';
   ```

   Sign out and in again, because the level is kept in the session. *Check: the account page shows `admin`.*

2. Add a view called `admin` (`view/admin.inc.php`), and register it in `index.php`.
   - A visitor who is not signed in goes to the sign in page, and comes back to the admin page after. (The login page's list of allowed redirect names needs `admin` too.)
   - A signed-in user who is not an admin goes to the products page.
   - Only an admin sees a link to it in the menu.

3. On the admin page, list every product with its price and whether it needs a deposit.

4. Let the admin add a product, with a form for the name, price, description, image file name and deposit.
   - Use `new Product(null, ...)` and `save()`. A `null` id means insert.
   - Refuse an empty name, and a price that is not a number or is below zero. Show a message, and keep the page working.
   - Only accept an image that looks like a file name, such as `masterclass_react.png`, so nobody can type `../../something`.

5. Let the admin edit a product. A link such as `index.php?view=admin&edit=2` fills the form with that product. Saving it updates the row.
   - You need a way to get one product by its id. Add a `static` `getById(int $id): ?Product` to `Product`, with a prepared statement.

6. Let the admin delete a product, after the browser asks "are you sure?".
   - Add `delete()` to `Product`.
   - A product that is in someone's cart cannot be deleted, because of the foreign key. The page must not crash. Catch the `PDOException`, check for code `23000`, and show a message.

7. Escape everything you print. Protect every form on the admin page with the secret value from Concepts.

8. Check it works from the visitor side: a product you add shows on the home page and can be enrolled on. A product you edit shows the new price in the cart of someone who already had it.

9. Remember to commit your work when you are done (see [How to submit](../../../../../README.md#how-to-submit)).

## Extra credit: forgot password

Add a "Forgot your password?" link to the sign in page.

- Make a table for reset requests: the user, a hash of a secret token, when it expires, and whether it was used. Store only a **hash** of the token (`hash('sha256', $token)`), so a leaked database cannot be used to reset anyone's password. Make the token with `bin2hex(random_bytes(32))`.
- A form takes an email address. If an account has it, create a token that works for an hour and "send" the link. There is no mail server in the dev container, so write the link to a file in `outbox/` (which is ignored by Git). The page says the same thing whether or not the email has an account.
- The link opens a page, with a form for a new password of at least 8 characters.
- A token works once, and only before it expires. After a good reset, go to the sign in page with a message.
- Run the check and the update in one transaction, so two people using the same link at once cannot both succeed.

## Done when

- [ ] Only an admin can open the admin page. Anyone else is sent away.
- [ ] An admin can add, edit and delete products, and bad input shows a message, not an error.
- [ ] Deleting a product that is in a cart shows a message.
- [ ] Your work is committed and pushed.
- [ ] Extra credit: a reset link works once, expires, and never says whether an email has an account.

## Previous / Next

Previous: [07 Cart: accounts, views and checkout](../07-accounts-views-checkout/README.md). Next: Unit 9, coming soon.
