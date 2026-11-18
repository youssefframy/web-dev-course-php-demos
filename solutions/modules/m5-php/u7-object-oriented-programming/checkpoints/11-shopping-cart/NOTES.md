# Notes: Unit 7 checkpoint 11

The answer is the class copy of the shopping cart, with these fixes.

- **Delete never matched.** `FILTER_SANITIZE_NUMBER_INT` returns a string, and `removeProduct(int)` compares with `===`. The answer reads ids with `FILTER_VALIDATE_INT` and checks for `false` and `null`.
- **Bad quantity.** `FILTER_VALIDATE_INT` gives `false` for text, which `?? 1` does not replace. An `is_int()` check does.
- **Unknown product.** `current(array_filter(...))` returns `false` for no match. The answer checks before calling `upsertProduct()`.
- **No reference juggling.** The slide's `foreach (... as &$cartItem)` with `$item = &$cartItem` is not needed: objects are handles, so changing `qty` inside a plain `foreach` changes the real item. It also leaves the reference to the last item hanging around, the problem from Unit 6 checkpoint 04.
- **`?string`.** The starter already uses it. The slides' `string $featuredImage = null` is deprecated from PHP 8.4.
- **Require before `session_start()`.** The starter does this. Reversing it gives `__PHP_Incomplete_Class` for the cart on the second request.
- **Output.** Names and descriptions go through `htmlspecialchars()`, and money through `number_format($value, 2)`.
- The forms posted to `/index.php?view=cart`, which breaks if the app is not served from the web root. The answer uses `index.php?view=cart`.

`removeProduct()` leaves gaps in the keys of `$cartItems`. Nothing depends on the keys, so the answer does not renumber them. `array_values()` would be fine.
