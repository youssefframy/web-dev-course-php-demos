# Checkpoint 06: Exercise 5.2

## Goal

Answer Exercise 5.2 against POS, with joins, grouping and sub-queries.

## Concepts

Group by the id, not by the name. Two clients can share a name, and grouping by name adds their purchases together. MariaDB also needs the name columns in the `GROUP BY` (see checkpoint 03), so write `GROUP BY Client.id, Client.name, Client.surname`.

`SUM()` of no rows is `NULL`, not 0, and anything minus `NULL` is `NULL`. `COALESCE(x, 0)` turns a `NULL` into 0.

## Before you start

```sh
./sql reset pos
```

## Steps

Write each answer in its file under `answers/`, then run `./sql check m4u5-cp06`.

1. `5.2.1.sql`: the id, name and surname of every client who has made a purchase.
2. `5.2.2.sql`: the name and surname of every client, with the number of purchases they made. Each invoice is one purchase. Clients with no purchases show 0. The check adds a second client called Lucy Pace, with one purchase, so grouping by name will show.
3. `5.2.3.sql`: the total still to be collected from clients: all invoices minus all payments, as one value.
4. `5.2.4.sql`: the id, name, surname and balance of every client who owes money. Leave out anyone who owes nothing. The check adds two clients first: Trevor Musu, with an unpaid invoice, and Brandon Pace, who has paid in full. Trevor must appear. Brandon must not.
5. `5.2.5.sql`: the total sales for each product category.
6. `5.2.6.sql`: every credit card payment received on the morning of 22 January 2022, with the item it paid for and the amount. Morning means before noon: `>= '2022-01-22 00:00:00' AND < '2022-01-22 12:00:00'`. The slides use `BETWEEN ... AND '11:59:59'`. That works here only because `issueTime` stores whole seconds. On a column that stores fractions, it misses 11:59:59.5.

## A known bug, kept for Unit 6

The slides' answer to 5.2.4 is:

```sql
SELECT
    Client.id, Client.name, Client.surname,
    (SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = Client.id) -
    (SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id) AS balance
FROM Client
HAVING balance IS NOT NULL;
```

On the original data it gives the right rows by luck. It has two faults. A client with invoices and no payments gets `NULL` and disappears, so Trevor is missing. A client who has paid in full has a balance of 0 and appears, so Brandon is there. Its `HAVING` with no `GROUP BY` is also a MariaDB extension that other databases reject. Unit 6 comes back to this query.

## Done when

- [ ] `./sql check m4u5-cp06` passes.
- [ ] You can say why the slides' 5.2.4 drops Trevor.

## Previous / Next

Previous: [05 Sub-queries](../05-subqueries/README.md). Next: [07 Exercise 5.3](../07-exercises-5-3/README.md).
