# Checkpoint 12: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to show the visitor a summary of their order on the checkout page. The extra credit emails it to them.

## Concepts

Nothing new. You need:

- `Cart`, `CartItem` and `Product` from checkpoint 11
- the session and `filter_input()` from Unit 5
- `FILTER_VALIDATE_EMAIL`, and `mail()` for the extra credit

## Before you start

You need the shopping cart from checkpoint 11. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u7-object-oriented-programming/checkpoints/11-shopping-cart/shoppingcart/. modules/m5-php/u7-object-oriented-programming/shoppingcart/
```

Start the server in `modules/m5-php/u7-object-oriented-programming/shoppingcart`.

## Steps

Today the checkout page empties the cart and says thank you, and nothing else.

1. Show a summary of the order on the checkout page: each product with its quantity and subtotal, and the total.
   - The cart is emptied on this page. Take what you need from it first.
   - Reloading the checkout page, or visiting it without an order, must not crash. Say there is nothing to show.
2. Show the deposit warning on the summary too, when it applies.
3. Escape every value you print, and show money with two decimals.
4. Extra credit: the checkout form already has an email field, but it has no `name`, so nothing is sent. Fix that, and send the visitor an email with the summary.
   - Refuse an address that is not valid. Do not let a line break through into the email headers.
   - `mail()` needs a mail server, and the dev container does not have one, so it may return `false`. Handle that on the page, and also write the email to a file so you can see what it would have said.

## Done when

- [ ] The checkout page lists what was bought, with the total, and a refresh does not crash.
- [ ] Extra credit: a valid address gets an email, an invalid one is refused with a message, and a `mail()` failure is handled.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [11 Shopping cart](../11-shopping-cart/README.md). Next: Unit 8, coming soon.
