
# Checkpoint 01: Views

## Goal

Save queries in the database as views, then use a view the way you would use a table.

## Concepts

A view is a query saved inside the database, under a name.

```sql
CREATE VIEW ViewName AS
SELECT ...;
```

- You can select from a view, join it, or use it in a sub-query, just like a table.
- A view saves the query, not its result. The query runs again every time you use the view, so the view is always up to date.
- A name can only be used once. To change a view, `DROP VIEW ViewName;` and create it again.

Start view names with `vw`, as the slides do. It tells you at a glance that `vwSoldItems` is a view and `InvoiceItem` is a table.

## Before you start

```sh
./sql reset pos
```

## Steps

Write each answer in its file under `answers/`. Script files hold the `CREATE VIEW`. Add a `SELECT * FROM` the view after it if you want to see the rows when you run the file in the shell.

1. Follow along: `vwSoldItems.sql` (script). Create `vwSoldItems` from the slides: the item name, unit price and quantity of every item sold, in that order. Then run `SELECT * FROM vwSoldItems;`.
2. `6.1.1.sql` (script): create `vwHighValueInvoices`, showing all columns of every invoice with a total of €1,000 or more. *Check: 2 rows, invoices 1 and 2.*
3. `6.1.2.sql` (script): create `vwInvoiceDetails`, showing for every invoice:
   - the invoice id, aliased as `invoiceId`
   - the client id, `clientId`
   - the client's `name` and `surname`
   - the cashier's name, aliased as `cashierName`
   - the invoice `total`

   *Check: 3 rows. Invoice 1 was served by Kristina, invoices 2 and 3 by Roger.*
4. `6.1.3.sql` (script): create `vwItemCategory`, showing each item's `id`, `name` and category name (`catName`).
5. `6.1.3-join.sql` (query): join `InvoiceItem` to `vwItemCategory`, showing the invoice item id, quantity, item name and category name. *Check: 4 rows. The Hololens is Entertainment.*
6. `6.1.4.sql` (query): list all clients, with an extra column `totalSpent`. Calculate it with a sub-query that adds up `total` from `vwInvoiceDetails` for that client. *Check: 6 rows. Lorraine has 1923.90 and Rachel has 1200.99. Note what the other clients show. You will fix this in checkpoint 04.*
7. Run `./sql check m4u6-cp01`.

The check for 6.1.1 adds an invoice of €2,000 after your view exists, then selects from the view again. The new invoice must appear without you changing the view.

## Done when

- [ ] `./sql check m4u6-cp01` passes.
- [ ] Your views exist in your own `pos`: run each file with `./sql shell pos < ...`. Checkpoint 02 selects from `vwInvoiceDetails` again.
- [ ] You can say what `totalSpent` shows for Lucy, and why.

## Previous / Next

Previous: [Unit 5](../../../u5-queries/README.md). Next: [02 Transactions](../02-transactions/README.md).
