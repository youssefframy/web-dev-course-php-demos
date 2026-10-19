# Checkpoint 05: Sub-queries

## Goal

Put one query inside another, as a column and as a table. Then compare a sub-query with the JOIN that gives the same result.

## Concepts

A sub-query is a `SELECT` in brackets inside another query.

- In the `SELECT` list, it produces one value per row. It must return at most one row and one column.
- In `FROM`, it produces a table. A table made this way must have an alias. `T` is common.

An alias is a name, and in MariaDB on Linux, table aliases are case-sensitive. The slides' sub-query names its table `T`, then refers to `t.toDate`. That fails with error 1054. Use one spelling throughout.

The slides' query also uses `fromDate` and `toDate`, but LeaveBooking, as you built it in Unit 4, has `dateFrom` and `dateTo`. It writes the date as `"2024-01-01"`, and a date should be in single quotes.

A column sub-query and a LEFT JOIN often give the same rows, as they do in this checkpoint. Prefer the JOIN, because it is easier to read and extend. The optimiser often runs both the same way. Do not assume either is always faster.

## Before you start

```sh
./sql reset joinlab
./sql rebuild iceadmin
```

## Steps

1. `answers/leave_subquery.sql`, on iceadmin: every employee's name and surname, with the dates of any leave booking that starts after 1 January 2024. Use a sub-query in `FROM` that picks the bookings, and LEFT JOIN Employee to it. Before you run it, predict how many of Fred's bookings will appear. One of them started in December 2023.
2. `answers/column_subquery.sql`, on joinlab: each employee's name and surname, and their department name as `dept`. Get `dept` from a sub-query in the `SELECT` list.
3. `answers/left_join_equivalent.sql`, on joinlab: the same result with a LEFT JOIN and no sub-query.
4. Run `./sql check m4u5-cp05`. Then compare your two joinlab results by eye.

## Done when

- [ ] `./sql check m4u5-cp05` passes.
- [ ] You can say when a column sub-query and a LEFT JOIN would give different results. Hint: what if a department id appeared twice in Department?

## Previous / Next

Previous: [04 Joins](../04-joins/README.md). Next: [06 Exercise 5.2](../06-exercises-5-2/README.md).
