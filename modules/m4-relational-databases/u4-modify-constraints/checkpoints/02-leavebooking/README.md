# Checkpoint 02: LeaveBooking

## Goal

Create a LeaveBooking table, link it to Employee with a foreign key, and see the database refuse a key that points at nobody.

## Concepts

A foreign key is a constraint. It says that every value in one column must already exist as a primary key in another table. `ALTER TABLE ... ADD CONSTRAINT` adds one to an existing table.

When you add a foreign key, MariaDB checks every row that is already in the table. If even one row points at a missing employee, the whole `ALTER` fails with error 1452 and nothing changes. You fix the data first, then add the constraint.

A constraint belongs to the table it is declared on. `c_leavebooking_employee` is declared on LeaveBooking, so you drop it from LeaveBooking. The slides drop it from Employee. That fails with error 1091, because Employee has no constraint of that name.

Constraints cannot be edited. To change one, drop it and add it again. Dropping a foreign key leaves behind the index MariaDB created for it. That is harmless, and it speeds up lookups by employee.

## Before you start

This checkpoint expects iceadmin after checkpoint 01. Run `./sql rebuild iceadmin`. If checkpoint 01 is not finished, the checks start from the Unit 3 state instead and tell you so.

## Steps

1. The table, with the name and types every later checkpoint expects:

   | Column | Type |
   |---|---|
   | id | `INT NOT NULL AUTO_INCREMENT PRIMARY KEY` |
   | dateFrom | `DATE NOT NULL` |
   | dateTo | `DATE NOT NULL` |
   | employeeId | `INT NOT NULL` |

2. Break it. In `answers/break_fk.sql`, create LeaveBooking with no foreign key. Insert the rows below, plus one more: a booking from 2024-01-10 to 2024-01-10 for employee 4. Then add the foreign key, named `c_leavebooking_employee`. Run `./sql check m4u4-02-breakit-fk` and read the error.

   The slides use employee 3 for the bad booking. In iceadmin, employee 3 is Fred, who exists, so employee 3 would not fail here. Employee 4 does not exist.

3. Fix it. In `db/iceadmin/u4_02_leavebooking.sql`, create the table, insert only the valid rows, then add the foreign key with `ALTER TABLE`. Run `./sql rebuild iceadmin`.

   | dateFrom | dateTo | employeeId |
   |---|---|---|
   | 2024-01-01 | 2024-01-02 | 2 |
   | 2024-08-12 | 2024-08-16 | 2 |
   | 2024-02-01 | 2024-02-03 | 1 |
   | 2024-03-01 | 2024-03-15 | 3 |
   | 2023-12-27 | 2023-12-29 | 3 |

   The first two rows are the slides' bookings for Lucy. The others give Joe and Fred some leave too, which Unit 5 queries.

4. Drop the constraint. In `answers/drop_constraint.sql`, drop `c_leavebooking_employee` from the right table. This runs on a scratch copy, so your iceadmin keeps its foreign key.
5. Run `./sql check m4u4-cp02`.

## Done when

- [ ] `./sql check m4u4-cp02` passes.
- [ ] You can say why adding the foreign key failed, and why dropping it from Employee fails too.

## Previous / Next

Previous: [01 Alter columns](../01-alter-columns/README.md). Next: [03 Vendor](../03-vendor/README.md).
