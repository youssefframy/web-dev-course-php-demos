# Notes: Unit 5 self-study

The reference answer includes the extra credit. Without it, a student keeps the single `convert_type` drop-down and adds three `<option>`s to it. Either version is fine.

## Validating the conversion type

- The reference answer lists every supported conversion once, in `$conversions`, in `includes/convert.inc.php`. The server checks against it with `in_array($conversionType, $conversions, true)`, and the extra-credit JavaScript receives the same list through `json_encode()`. Adding a conversion means one new `case` in `convert()` and one new entry in the array.
- Without the extra credit, checking against the `case` labels in a `switch`, or a `default:` that adds an error, is also fine, as long as an unknown type never reaches the end of `convert()`. In checkpoint 09 it does, and PHP warns about the undefined `$convertedValue`.
- The third argument of `in_array()`, `true`, makes it compare with `===`. It does not matter much here, because every value is a string, but it is a good habit.

## Validating the accuracy

- `FILTER_VALIDATE_INT` with `min_range` 0 and `max_range` 5 returns an `int`, or `false` for anything else: `9`, `-1`, `2.5` and `abc`. A missing field gives `null`, so the reference answer checks for both.
- Casting with `(int)` and then checking `>= 0 && <= 5` is a common answer, but `(int) '2.5'` is `2` and `(int) 'abc'` is `0`, so bad input is quietly accepted. The filter is the better answer.
- The accuracy is only stored when every check passes, so a rejected request leaves the saved accuracy unchanged.

## New conversions

- Inches to centimetres multiplies by 2.54, the opposite of `cm_to_in`.
- The reference answer uses 1 EUR = 0.85 GBP, and divides by the same rate for the other direction, so converting there and back gives the starting value. Any rate between about 0.8 and 0.9 is acceptable.
- The units are ` EUR` and ` GBP`, with a leading space, so the result reads `100.00 EUR = 85.00 GBP`. `€` and `£` placed after the number also work.

## Extra credit: From and To

- The form sends `convert_from` and `convert_to`. The server joins them into `"{$from}_to_{$to}"` and checks that against `$conversions`, so the rest of the code, `convert()` and the history, did not change.
- `filterToOptions()` runs once when the page loads, and again on every `change` of the From list. For each To option, it checks whether `from_to_to` is in the list. Options that are not get both `hidden`, which removes them from the list, and `disabled`, because some browsers still let you pick a hidden option with the keyboard.
- If the selected To option was just hidden, the first allowed one is selected instead.
- Both drop-downs are sticky: after a conversion, the same From and To are selected.
- Building the To list from scratch with `document.createElement('option')` instead of hiding options is also fine.
