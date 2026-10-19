# Checkpoint 04: Joins

## Goal

Combine rows from two tables with each kind of join. Predict each result before you run it, then use the same joins on iceadmin.

## Concepts

joinlab has five employees and four departments. Roger has no department, and nobody works in Marketing.

- `JOIN` (also `INNER JOIN`) returns only the pairs that match. Roger and Marketing both drop out. The slides' summary says it returns rows from the left table plus any matches. That describes a LEFT JOIN.
- `LEFT JOIN` returns every row of the table before it, matched where possible. Roger appears with `NULL` for his department.
- `RIGHT JOIN` returns every row of the table after it. Marketing appears with `NULL` for the employee.
- A full outer join returns every row of both tables. MariaDB 11.4 has no `FULL OUTER JOIN`, so you build one: the LEFT JOIN query, then `UNION`, then the RIGHT JOIN query. `UNION` removes the matched rows that appear in both halves.

The slides write `FROM Employee FULL JOIN Department`. In MariaDB, `FULL` is not a join keyword there. It becomes an alias for Employee, and what follows is a plain JOIN. With the slides' column names the query fails with error 1054, because Employee is now called FULL. With unqualified column names it runs silently as an INNER JOIN, which is worse. `FULL OUTER JOIN` is a syntax error, 1064.

In iceadmin, an employee links to departments through EmployeeDepartment, so each query needs two joins.

## Before you start

```sh
./sql reset joinlab
./sql rebuild iceadmin
```

iceadmin should be at the end of Unit 4, with Lucy deleted. If it is not, `./sql reset iceadmin --at m4u4`.

## Steps

1. Open `PREDICT.md`. For each question, write your prediction, then run your query with `./sql shell joinlab` or `./sql shell iceadmin` and record what happened.
2. On joinlab, list each employee's name, surname and department name (as `dept`):
   - `answers/inner.sql` with `JOIN`
   - `answers/left.sql` with `LEFT JOIN`
   - `answers/right.sql` with `RIGHT JOIN`
   - `answers/full.sql`, a full outer join built with `UNION`
3. On iceadmin:
   - `answers/bridge_inner.sql`: every employee with each department they work in. Show the name, surname and department name.
   - `answers/bridge_left.sql`: every department with each of its employees. Start from Department, so a department with nobody in it still appears.
   - `answers/lucy.sql`: every leave booking, with its dates and the employee's name and surname. Start from LeaveBooking with a LEFT JOIN.
4. Run `./sql check m4u5-cp04`.

The iceadmin checks run the reference query on your own iceadmin and compare the two results. So the expected result is whatever your Unit 4 work left in the database.

## Done when

- [ ] `./sql check m4u5-cp04` passes.
- [ ] `PREDICT.md` is complete, and you can say where Lucy went.

## Previous / Next

Previous: [03 Aggregates and GROUP BY](../03-aggregates-group-by/README.md). Next: [05 Sub-queries](../05-subqueries/README.md).
