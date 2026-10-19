# Unit 5: Queries

In this unit you ask questions of databases with `SELECT`. You start with one table, then add filtering, sorting, aggregates, joins and sub-queries.

You work on four databases:

| Database | Where it comes from |
|---|---|
| pos | A department store's point of sale system, provided. |
| joinlab | The two small tables from the join slides, provided. |
| iceadmin | Yours, as Unit 4 left it: LeaveBooking and Vendor added, and Lucy deleted. |
| tvstudio | Yours, as your Unit 4 migration left it. |

## Where you start

```sh
./sql reset pos
./sql reset joinlab
./sql rebuild iceadmin
./sql rebuild tvstudio
```

If your Unit 4 work is not finished, load the reference state instead. The checks do the same automatically, and say so.

```sh
./sql reset iceadmin --at m4u4
./sql reset tvstudio --at m4u4
```

## How the checks work

Each exercise is one `SELECT` in a file under `answers/`. `./sql check` runs it on a scratch copy and compares the rows with the expected result.

- Column names are only compared when the exercise asks for an alias.
- Row order is only compared when the exercise asks for an order.
- Some checks add a few rows to the scratch copy first, to test edge cases. The README says when.

Two rules apply to every query in this unit:

- Write string and date values in single quotes: `'2024-01-01'`, not `"2024-01-01"`. Some servers read double quotes as a column name.
- Write quotes as plain `'`. Curly quotes copied from a slide (`‘ ’`) are not SQL.

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [A tour of POS](checkpoints/01-pos-tour/README.md) | The POS diagram, and three warm-up queries |
| 02 | [SELECT, WHERE and ORDER BY](checkpoints/02-select-where-order/README.md) | Exercise 5.1 |
| 03 | [Aggregates and GROUP BY](checkpoints/03-aggregates-group-by/README.md) | Totals per group, and the two GROUP BY errors |
| 04 | [Joins](checkpoints/04-joins/README.md) | INNER, LEFT, RIGHT and full joins, then joins on iceadmin |
| 05 | [Sub-queries](checkpoints/05-subqueries/README.md) | Queries inside queries, and their JOIN equivalents |
| 06 | [Exercise 5.2](checkpoints/06-exercises-5-2/README.md) | Six questions about POS clients and sales |
| 07 | [Exercise 5.3](checkpoints/07-exercises-5-3/README.md) | Five questions about VAT, profit and categories |
| 08 | [Querying tvstudio](checkpoints/08-tvstudio-queries/README.md) | Five questions about your own database |

Back to [Module 4](../README.md).
