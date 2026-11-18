# Notes: Unit 7 self-study

The reference answer includes the extra credit. Without it, a student only needs the order summary, in `view/checkout.inc.php`.

## Summary

- The cart is emptied on the checkout page, so the page first copies what it needs into an `$order` object. It takes the items, the total and whether a deposit applies, then empties the cart.
- `CartItem` objects are shared, not copied, but the cart is replaced by `new Cart()`, so the old items are untouched and safe to print.
- A reload sends the form again, finds an empty cart, and says there is no order to show. A visit without a POST does the same. Redirecting after the POST (the PRG pattern) is a different approach, and would need the summary kept in the session.

## Email

- The checkout form's email field needs `name="email"`. It is also `required`.
- `FILTER_VALIDATE_EMAIL` refuses an address with a line break, so a visitor cannot add their own headers to the `mail()` call.
- `mail()` needs a mail server. Without one, it returns `false`, and the page says so. It may also return `true` when a local mail agent accepts the message and fails later, so a `true` does not prove delivery.
- The answer also writes every email to `outbox/`, so a student can read it. `outbox/` is ignored by Git.
- An acceptable alternative is to skip `mail()` completely and only write the file.
- The email body is plain text, with `EUR` rather than `€`, because the headers do not declare a character set.
