# Checkpoint 04: TRUNCATE and DROP

## Goal

Empty a table with `TRUNCATE`, remove one with `DROP`, and find out when the database refuses either.

## Concepts

- `DELETE FROM t` removes rows one at a time. It can have a `WHERE`, and it keeps the `AUTO_INCREMENT` counter where it was.
- `TRUNCATE TABLE t` removes every row at once and resets the counter to 1. The table stays.
- `DROP TABLE t` removes the table, its data and its constraints.

TRUNCATE and DROP are DDL. They commit straight away, and `ROLLBACK` cannot bring the data back.

Foreign keys protect the tables they point at. You cannot `TRUNCATE` a table that any foreign key references, even if the referencing table is empty: error 1701. You cannot `DROP` it either: error 1451. TRUNCATE is only allowed on a table nothing references, such as LeaveBooking or Vendor. Those tables hold foreign keys of their own, but nothing points at them.

You may see advice to run `SET FOREIGN_KEY_CHECKS = 0` first. That makes the TRUNCATE work and leaves every child row pointing at an employee who no longer exists. Do not do it.

Everything in this checkpoint runs on scratch copies. Your iceadmin is not changed, and nothing here is part of the chain.

## Before you start

This checkpoint expects iceadmin after checkpoint 03. Run `./sql rebuild iceadmin`.

## Steps

1. Break it. In `answers/truncate_employee.sql`, truncate Employee. Run `./sql check m4u4-04-breakit-truncate` and read which constraint stopped you.
2. In `answers/truncate_safe.sql`, truncate LeaveBooking, then insert one booking: 2024-06-03 to 2024-06-04 for employee 1. The check looks at the id the new booking gets. Predict it first, then run `./sql check m4u4-04-truncate-safe`.
3. In `answers/drop_table.sql`, drop the two tables nothing references and nobody needs yet: first Vendor, then LeaveBooking. Then run `./sql check m4u4-04-drop`.
4. Write one sentence in your own words in `answers/truncate_safe.sql`, as a comment: when is TRUNCATE safe?

## Done when

- [ ] `./sql check m4u4-cp04` passes.
- [ ] You can say why the booking got the id it did after TRUNCATE, and what id it would get after `DELETE FROM LeaveBooking` instead.

## Previous / Next

Previous: [03 Vendor](../03-vendor/README.md). Next: [05 Referential actions](../05-referential-actions/README.md).
