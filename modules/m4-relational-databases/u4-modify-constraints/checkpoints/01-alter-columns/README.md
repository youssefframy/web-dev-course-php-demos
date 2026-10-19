# Checkpoint 01: Alter columns

## Goal

Add a column to a table that already has rows, change its type, then remove it.

## Concepts

`ALTER TABLE` changes a table's structure and keeps its data. There are three forms:

- `ADD` adds a column.
- `CHANGE COLUMN old new definition` renames a column, changes its definition, or both. You always give the full definition again, including `NOT NULL`.
- `DROP COLUMN` removes a column and every value in it.

A new column has to hold something for the rows that already exist. If the column allows `NULL`, they get `NULL`. If it has a `DEFAULT`, they get the default. A `NOT NULL` column with no default is the interesting case, so you will predict that one before you run it.

`ALTER TABLE` is DDL, so it commits straight away. `ROLLBACK` cannot undo it.

## Before you start

This checkpoint expects iceadmin as it was at the end of Unit 3. If you are not sure that is what you have, run:

```sh
./sql reset iceadmin --at m4u3
```

## Steps

1. Open `PREDICT.md` in this folder. Answer question 1 before you run anything.
2. In `db/iceadmin/u4_01_add_column.sql`, add an `employmentStart` column to Employee, of type `DATETIME NOT NULL`.
3. Run your steps, then look at the rows:

   ```sh
   ./sql rebuild iceadmin
   echo "SELECT id, name, employmentStart FROM Employee;" | ./sql shell iceadmin
   ```

   Write what you see in `PREDICT.md`.
4. In `db/iceadmin/u4_01_change_column.sql`, change `employmentStart` to `DATE NOT NULL`. Rebuild, then look at the rows again.
5. In `db/iceadmin/u4_01_drop_column.sql`, drop the column. Rebuild.
6. Run `./sql check m4u4-cp01`.

Do not put `USE iceadmin;` in these files. `./sql` runs each file in the right database for you.

## Done when

- [ ] `PREDICT.md` has your predictions and what you observed, and every box is ticked.
- [ ] `./sql check m4u4-cp01` passes.
- [ ] `./sql rebuild iceadmin` ends with Employee back to its Unit 3 columns.

## Previous / Next

Previous: [Unit 3](../../../u3-physical-design/demo/README.md). Next: [02 LeaveBooking](../02-leavebooking/README.md).
