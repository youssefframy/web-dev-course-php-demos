# Notes: Unit 7 checkpoint 03

## Tax formula

The slides compute `(gross - subtract) * rate / 100`. The `subtract` amounts only make sense as `gross * rate / 100 - subtract`: at the top of the first band, 9100 × 15% − 1365 = 0, which is where the previous band ends. The answer uses that form.

## Continuity of the table

Checked at each boundary, comparing the last euro of one band with the next band's formula:

| Boundary | Lower band | Upper band |
|---|---|---|
| 9,100 | 0.00 | 0.00 |
| 14,500 | 810.00 | 810.00 |
| 19,500 | 2,060.00 | 2,150.00 |
| 60,000 | 12,275.00 | 12,275.00 |

The jump of 90 at 19,500 comes from the numbers in the slides (25% with 2,815, then 25% with 2,725). The answer keeps the slide data, since this is a coding exercise and not a tax table. If a student asks, this is why.

## Gaps

The slide brackets stop at 9100 and restart at 9101, so a wage of 9100.50 matches nothing. The answer looks for the highest bracket whose `from` is at or below the wage, which leaves no gap. It starts at 0, so only a negative wage reaches the final `return 0`.

## Other changes from the slides

- `TAX_RATE = 'Individual'` is unused, so it is dropped.
- The setter for the rate accepts 0 (`>= 0`), because the constructor's default is 0.
- The constructor calls `setHourlyRate()`, so the rule lives in one place.
