
# Checkpoint 03: notes

## 6.3.6: the gap between €999 and €1,000

The workbook's answer splits Entertainment with `unitPrice <= 999` and `unitPrice >= 1000`. Prices have cents, so an item at €999.50 matches neither line and falls through to `Delivery not available`.

The reference answer checks the €1,000 line first, then catches every other Entertainment item with `Category.catCode = 'ENT'` alone. Because the `WHEN` lines are checked in order, the second line only sees items under €1,000. The same idea is behind the order of the lines in 6.3.1.

`unitPrice < 1000` on the first line also works. The check passes either version, because no invoice item in `pos` is priced between €999 and €1,000.

## 6.3.2, 6.3.4 and 6.3.5: the form is not checked

The checks compare rows, so a searched case passes 6.3.2, and a `CASE` passes 6.3.4. Read the student's file to see which form they used.
