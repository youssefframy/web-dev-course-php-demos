# Checkpoint 01: A tour of POS

## Goal

See the POS database as a diagram, then write your first queries against it.

## Concepts

POS is the point of sale system of a department store.

- Item holds the products, and Category groups them.
- Client and Cashier hold people.
- Each sale is an Invoice, with one InvoiceItem row per product sold.
- A client can pay in instalments. Each Payment has a PaymentType, and PaymentAllocation says how much of a payment went towards each invoice item.

A `SELECT` names the columns you want. `FROM` names the table, `WHERE` filters the rows, and `ORDER BY` sorts them. The answers in this unit always put the table name in front of the column, as in `Client.surname`. It is optional with one table, and necessary once there are several.

## Before you start

```sh
./sql reset pos
```

## Steps

1. Run `./sql sql2dbml pos`. It writes `db/pos/pos.dbml` from the database.
2. Paste `db/pos/pos.dbml` into <https://dbdiagram.io>. Find the path from Client to Item: Client, Invoice, InvoiceItem, Item.
3. Write each warm-up in its file under `answers/`:
   1. `warmup_1.sql`: every client's surname and name, sorted by surname, then by name.
   2. `warmup_2.sql`: the name and price of every item that costs more than €100.
   3. `warmup_3.sql`: the id, total and issue time of every invoice issued by Roger Vassallo.
4. Run `./sql check m4u5-cp01`.

## Done when

- [ ] `./sql check m4u5-cp01` passes.
- [ ] You have seen the POS diagram, and can say which table links Payment to InvoiceItem.

## Previous / Next

Previous: [Unit 4](../../../u4-modify-constraints/README.md). Next: [02 SELECT, WHERE and ORDER BY](../02-select-where-order/README.md).
