
# Unit 6: Further SQL

In this unit you save queries as views, group changes into transactions, choose values with `CASE` and `IF()`, and replace missing values with `COALESCE()`.

You work on one database:

| Database | Where it comes from |
|---|---|
| pos | The department store's point of sale system from Unit 5. |

## Where you start

```sh
./sql reset pos
```

You start with 3 invoices, 4 invoice items and 3 payments. Do the checkpoints in order. Checkpoint 02 adds invoices and a payment to your `pos`, and checkpoints 03 and 04 use them.

If you reset `pos` after checkpoint 02, load the same rows again:

```sh
./sql reset pos
./sql shell pos < modules/m4-relational-databases/u6-further-sql/part2-data.sql
```

## How the checks work

Each exercise is a file under `answers/`. `./sql check` runs it on a scratch copy of `pos` and checks the result. Each checkpoint's README says which kind of file each exercise is.

- A **query** file holds one `SELECT`. Its rows are compared with the expected result. Column names are only compared when the exercise asks for an alias, and row order is not compared.
- A **script** file holds any statements: `CREATE VIEW`, `START TRANSACTION`, `INSERT`, `COMMIT`. The check runs it, then looks at the database: does the view exist, what does it return, which rows were saved?

The scratch copy always starts from a fresh `pos`. Checkpoints 03 and 04 add the checkpoint 02 rows to it first, and create any view from an earlier exercise that the question uses. A mistake in one exercise therefore does not fail the next one.

To make the same change to your own `pos`, run the file in the shell:

```sh
./sql shell pos < modules/m4-relational-databases/u6-further-sql/checkpoints/01-views/answers/6.1.1.sql
```

The rules from Unit 5 still apply. Write strings in plain single quotes (`'Van'`), and don't put `USE pos;` in your files, because `./sql` picks the database for you.

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Views](checkpoints/01-views/README.md) | `vwSoldItems`, and Exercise 6.1 |
| 02 | [Transactions](checkpoints/02-transactions/README.md) | Francesco's invoice, and Exercise 6.2 |
| 03 | [Boolean logic](checkpoints/03-boolean-logic/README.md) | `CASE`, `AND`, `OR` and `IF()`, and Exercise 6.3 |
| 04 | [Using COALESCE](checkpoints/04-coalesce/README.md) | Fixing `vwDebtors`, and Exercise 6.4 |

Back to [Module 4](../README.md).
