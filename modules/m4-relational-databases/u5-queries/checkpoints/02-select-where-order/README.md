# Checkpoint 02: SELECT, WHERE and ORDER BY

## Goal

Answer Exercise 5.1 against POS.

## Concepts

- `WHERE` keeps the rows for which a condition is true.
- `ORDER BY` sorts, ascending unless you add `DESC`. Each column needs its own `DESC`.
- `AS` gives a column a name in the result. A computed column has no useful name without one.
- `SUM()` adds a whole column into one value. `GROUP BY` makes it add per group instead.

Formatting with `CONCAT('€', ...)` turns a number into text. Text sorts character by character, so '€9.50' sorts after '€10.25'. Format numbers for display only, and sort on the number itself.

## Before you start

```sh
./sql reset pos
```

## Steps

Write each answer in its file under `answers/`, then run `./sql check m4u5-cp02`.

1. `5.1.1.sql`: every invoice with a total of €1,000 or more.
2. `5.1.2.sql`: the sub-total (unit price times quantity) of each invoice item, with its id. Show the sub-total as euros with `CONCAT('€', ...)`, and name the column `subTotal`.
3. `5.1.3.sql`: the total of all sales, as one value named `TotalSales`.
4. `5.1.4.sql`: the total of payments received on each day, oldest day first. The slides' question asks for a grand total "sorted by date", and their answer has no `ORDER BY`. This version asks for daily totals in date order, which is what the answer was trying to do.

When you have tried all four, compare with the reference: `solutions/modules/m4-relational-databases/u5-queries/checkpoints/02-select-where-order/exercise-5.1.sql`. It has every answer and its expected result, and runs in one go:

```sh
./sql shell pos < solutions/modules/m4-relational-databases/u5-queries/checkpoints/02-select-where-order/exercise-5.1.sql
```

## Done when

- [ ] `./sql check m4u5-cp02` passes.

## Previous / Next

Previous: [01 A tour of POS](../01-pos-tour/README.md). Next: [03 Aggregates and GROUP BY](../03-aggregates-group-by/README.md).
