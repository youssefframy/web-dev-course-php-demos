
# Checkpoint 03: Boolean logic

## Goal

Choose a value for each row with `CASE` and `IF()`, combining conditions with `AND` and `OR`.

## Concepts

A boolean statement evaluates to true or false. `CASE` comes in two forms:

```sql
-- Searched case: a full condition after each WHEN.
CASE
    WHEN Invoice.total >= 1000 THEN 'High'
    ELSE 'Low'
END AS invoiceValue

-- Simple case: one column after CASE, and a value to compare it with after each WHEN.
CASE Category.catCode
    WHEN 'APP' THEN 'Van'
    ELSE 'Parcel'
END AS deliveryMode
```

- The `WHEN` lines are checked in order, and the first one that is true wins. Put the narrowest condition first.
- `AND` means every condition must be true. `OR` means at least one must be true.
- `IF(expression, valueIfTrue, valueIfFalse)` handles a single either/or choice.
- `CASE` is part of the ISO SQL standard. `IF()` is a MariaDB and MySQL extension.

## Before you start

Your `pos` from checkpoint 02. If you reset it, reload the rows (see the [unit README](../../README.md#where-you-start)). The checks add the same rows themselves: invoice 6 is Francesco's, 7 is Brandon's, and invoice items 5 to 8 belong to them.

## Follow along

Run these from the slides in `./sql shell pos`:

1. The `invoiceValue` query.
2. `answers/vwDebtors.sql` (script): the `vwDebtors` view (CASE Example 2). Run it in your own `pos` too, because checkpoint 04 uses it. Copy it from here if your slide copy has curly quotes:

   ```sql
   CREATE VIEW vwDebtors AS
   SELECT
       T.*,
       CASE
           WHEN T.balance <= 499 THEN 'Sales'
           ELSE 'Finance'
       END AS Department
   FROM
       (
           SELECT
               Client.id, Client.name, Client.surname,
               (SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = Client.id) -
               (SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id) AS balance
           FROM
               Client
           HAVING
               balance IS NOT NULL
       ) AS T;
   ```

3. The delivery mode query, in both its searched and simple case versions.

## Steps

The query checks compare results only. They can't tell which form of `CASE` you used, so follow each question's instructions.

1. `6.3.1.sql` (query): all invoices, with an extra column `invoiceSize`. It is `Large` when the total is €1,000 or more, `Medium` when it is €100 or more, and `Small` otherwise. *Check: invoices 1 and 2 are Large, 3 and Francesco's are Medium, and Brandon's is Small.*
2. `6.3.2.sql` (query): use a **simple case** on `PaymentType.typeName`. List every payment's id, total and payment type, with a column `channel`: Cash → `In store`, Cheque → `In store`, Credit → `Card`, and anything else → `Online`. *Check: 4 rows. Three are In store and one is Card.*
3. `6.3.3.sql` (query): every invoice item's id, item name, quantity and unit price, with a column `handling`:
   - `Bulk discount` when the quantity is 5 or more **and** the unit price is €50 or less
   - `Manager approval` when the unit price is €1,000 or more **or** the quantity is 10 or more
   - `Standard` otherwise

   *Check: the plates and the spoons get Bulk discount, the TV and the fridge need Manager approval, and the rest are Standard.*
4. `6.3.4.sql` (query): use `IF()`. Every item's name and price, with a column `priceBand`: `Premium` when the price is €500 or more, `Standard` otherwise. *Check: only the Sony TV and the fridge are Premium.*
5. `6.3.5.sql` (query): question 4 again, with `CASE` instead of `IF()`. Then answer the question in `ANSWERS.md`.
6. `6.3.6.sql` (script): create `vwDeliveryPlan`, implementing the **revised** delivery table from the slides. Show the invoice item `id`, `unitPrice`, item `name`, `catCode` and a `deliveryMode` column, with these exact labels:

   | Category | Value | Delivery mode |
   |---|---|---|
   | Entertainment | Under €1,000 | Large Parcel by Courier |
   | Entertainment | €1,000 or more | Van |
   | Cutlery or Crockery | | Fragile Parcel |
   | Appliances | | Van |
   | Anything else | | Delivery not available |

   Then select the rows from the view where `deliveryMode` is `Van`. *Check: 3 rows: the TV, the fridge and the toaster.*
7. Run `./sql check m4u6-cp03`.

## Done when

- [ ] `./sql check m4u6-cp03` passes.
- [ ] `vwDebtors` exists in your own `pos`.
- [ ] You can say what 6.3.1 gives if the `Medium` line comes before the `Large` line.

## Previous / Next

Previous: [02 Transactions](../02-transactions/README.md). Next: [04 Using COALESCE](../04-coalesce/README.md).
