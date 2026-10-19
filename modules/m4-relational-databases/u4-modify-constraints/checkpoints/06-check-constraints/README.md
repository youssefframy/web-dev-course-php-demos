# Checkpoint 06: CHECK constraints

## Goal

Add rules that the values in a row must follow, and see the database reject a row that breaks one.

## Concepts

A foreign key is only one kind of constraint. iceadmin already uses the others:

| Constraint | What it guarantees | Example in iceadmin |
|---|---|---|
| `PRIMARY KEY` | Every row has a unique, non-null id. | `Employee.id` |
| `UNIQUE` | No two rows share a value, or a combination of values. | `UNIQUE (employeeId, departmentId)` on EmployeeDepartment |
| `NOT NULL` | The column always has a value. | `Employee.name` |
| `FOREIGN KEY` | The value exists in another table. | `Salary.employeeId` |
| `CHECK` | A condition on the row is true. | added in this checkpoint |

A `CHECK` holds any condition on the row's own columns. When you add one, MariaDB tests every row already in the table. If any row fails, the `ALTER` fails with error 4025 and the constraint is not added. Afterwards, any insert or update that breaks the rule fails with the same error.

Like foreign keys, CHECK constraints cannot be edited. Drop them and add them again.

## Before you start

This checkpoint expects iceadmin after checkpoint 05, with Lucy deleted. Run `./sql rebuild iceadmin`.

## Steps

1. In `db/iceadmin/u4_06_check_constraints.sql`, add two named constraints:
   - `c_leavebooking_dates` on LeaveBooking: a booking cannot end before it starts.
   - `c_salary_amount` on Salary: the amount is more than zero.
2. Run `./sql rebuild iceadmin`. Both constraints are added, because every existing row already follows them.
3. Break it. In `answers/bad_booking.sql`, insert a booking for employee 1 that starts on 2024-05-02 and ends on 2024-05-01. Run `./sql check m4u4-06-breakit-check` and read the error.
4. Run `./sql check m4u4-cp06`.

## Done when

- [ ] `./sql check m4u4-cp06` passes.
- [ ] You can name the five kinds of constraint, and give an iceadmin example of each.

## Previous / Next

Previous: [05 Referential actions](../05-referential-actions/README.md). Next: [07 tvstudio evolves](../07-tvstudio-evolves/README.md).
