# Checkpoint 03: Aggregates and GROUP BY

## Goal

Use aggregate and scalar functions, and see the errors that stop an aggregate query from returning wrong data.

## Concepts

An aggregate function turns many rows into one value: `SUM()`, `COUNT()`, `AVG()`, `MAX()` and `MIN()`. A scalar function works on one row at a time: `CONCAT()`, `UPPER()`, `ROUND()`, `COALESCE()` and others.

A query that mixes an aggregate with an ordinary column needs a `GROUP BY` that lists every ordinary column. Without that rule, some servers return a value picked from an arbitrary row, and the result looks right but is wrong. MariaDB enforces the rule when `ONLY_FULL_GROUP_BY` is on. It is on in this course: `docker-compose.yml` adds it to the server's other modes.

There are two different errors:

- 1140: an aggregate mixed with ordinary columns, and no `GROUP BY` at all.
- 1055: a `GROUP BY` that leaves out one of the ordinary columns.

The slides turn the mode on with `SET sql_mode = 'ONLY_FULL_GROUP_BY';`. Do not copy that. It replaces every other mode, including `STRICT_TRANS_TABLES`, which is what made the Unit 4 Vendor insert fail instead of silently cutting names short. To add a mode for your session only, use:

```sql
SET SESSION sql_mode = CONCAT('ONLY_FULL_GROUP_BY,', @@sql_mode);
```

MariaDB does not work out that a name depends on an id. If you group by `Client.id`, you must still list `Client.name` and `Client.surname` in the `GROUP BY`. MySQL 8 can work this out. MariaDB 11.4 cannot.

An alias with a space in it goes in quotes: `AS 'Total Sales'`.

## Before you start

```sh
./sql reset pos
```

## Steps

1. Break it. In `answers/break_no_group_by.sql`, select each invoice's `cashierId` and `SUM(Invoice.total)`, with no `GROUP BY`. Run `./sql check m4u5-03-breakit-group` and read the error.
2. Break it again. In `answers/break_missing_column.sql`, select `cashierId`, `issueTime` and `SUM(Invoice.total)`, grouped by `cashierId` only. Run `./sql check m4u5-03-breakit-group-by`.
3. `answers/average_invoice.sql`: the average invoice total, rounded to two decimal places and shown as euros, in a column named `averageInvoice`.
4. `answers/sales_by_cashier.sql`: each `cashierId` with the total of their invoices, in a column named `Total Sales`.
5. Run `./sql check m4u5-cp03`.

## Done when

- [ ] `./sql check m4u5-cp03` passes.
- [ ] You can say which of the two errors each query raised, and why.

## Previous / Next

Previous: [02 SELECT, WHERE and ORDER BY](../02-select-where-order/README.md). Next: [04 Joins](../04-joins/README.md).
