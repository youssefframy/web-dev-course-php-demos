# Checkpoint 07: Exercise 5.3

## Goal

Answer Exercise 5.3 against POS: calculations, money formatting, and invoices that meet two conditions at once.

## Concepts

`DECIMAL(10,2) * 1.18` keeps four decimal places: 1099.99 × 1.18 is 1297.9882. Round money to two places with `ROUND(x, 2)` before you show it.

`CONCAT('€', x)` makes text. Text sorts character by character, so '€950.00' comes before '€2024.90' in descending order. Show the formatted text, but sort on the number: `ORDER BY SUM(Invoice.total) DESC`.

To find invoices that have items from two categories, test each condition separately with `EXISTS`. A join through InvoiceItem gives one row per matching item, so an invoice with two entertainment items appears twice.

## Before you start

```sh
./sql reset pos
```

## Steps

Write each answer in its file under `answers/`, then run `./sql check m4u5-cp07`.

1. `5.3.1.sql`: every invoice's id, its total as euros, and its total with 18% VAT added, as euros rounded to two places. Invoice totals exclude VAT.
2. `5.3.2.sql`: the revenue and profit from entertainment products sold in 2022, assuming a 40% profit margin. Show both as numbers in columns named `revenue` and `profit`, with the profit rounded to two places. Only entertainment items count. The slides' answer multiplies the revenue by 1, which changes nothing, so leave that out.
3. `5.3.3.sql`: each cashier's name, surname and total sales as euros, highest sales first. The check adds a second cashier called Roger Vassallo, with one €950.00 sale.
4. `5.3.4.sql`: how much each client has spent in each category, with the name, surname, category name and the amount as euros. The check adds a second Lucy Pace, and gives both Lucys the same purchase.
5. `5.3.5.sql`: every invoice that has items from both the entertainment (`ENT`) and crockery (`CRK`) categories, each invoice once. The check adds an invoice with two entertainment items and one crockery item.

## Done when

- [ ] `./sql check m4u5-cp07` passes.
- [ ] You can say why the slides' 5.3.5 lists the added invoice twice.

## Previous / Next

Previous: [06 Exercise 5.2](../06-exercises-5-2/README.md). Next: [08 Querying tvstudio](../08-tvstudio-queries/README.md).
