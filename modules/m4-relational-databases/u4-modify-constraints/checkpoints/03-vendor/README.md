# Checkpoint 03: Vendor

## Goal

Add a Vendor table, watch an insert fail because a column is too short, then widen the column and insert again.

## Concepts

A single `INSERT` with several rows is atomic. Either every row goes in or none does. When one row is too long for its column, MariaDB rejects the whole statement with error 1406.

`AUTO_INCREMENT` hands out ids when an insert starts, not when it succeeds. A failed insert can use up ids, leaving a gap. How many it uses depends on the server setting `innodb_autoinc_lock_mode`. With the default value of 1, a multi-row insert reserves one id per row before it runs. Gaps are harmless. A primary key has to be unique, not sequential.

Vendor's `brandManager` column is a foreign key to Employee. Every vendor below points at an employee who exists, so this time the foreign key causes no trouble.

## Before you start

This checkpoint expects iceadmin after checkpoint 02. Run `./sql rebuild iceadmin`.

## Steps

1. The table, as the slides' SQL defines it:

   | Column | Type |
   |---|---|
   | id | `INT NOT NULL AUTO_INCREMENT PRIMARY KEY` |
   | name | `VARCHAR(20) NOT NULL` |
   | website | `VARCHAR(50) NOT NULL` |
   | country | `VARCHAR(30) NOT NULL` |
   | brandManager | `INT NOT NULL`, foreign key `c_vendor_employee` to `Employee (id)` |

   The slide's diagram disagrees with the SQL about `website`. Use `VARCHAR(50)`, as the SQL does. The longest website below is 37 characters.

2. The four vendors, in one `INSERT`:

   | name | website | country | brandManager |
   |---|---|---|---|
   | Microsoft | https://microsoft.com | USA | 3 |
   | Linux Professional Institute | https://lpi.org | USA | 3 |
   | Digital Marketing Institute | https://digitalmarketinginstitute.com | Ireland | 2 |
   | Adobe | https://adobe.com | USA | 1 |

3. Break it. In `answers/break_length.sql`, create the table and run the insert. Run `./sql check m4u4-03-breakit-length` and read the error.
4. Open `PREDICT.md` and answer both questions.
5. Watch it happen. Open `./sql shell iceadmin` and type the statements from step 3 by hand. Then:
   - run `SELECT * FROM Vendor;` to see what the failed insert left
   - widen `name` with `ALTER TABLE Vendor CHANGE COLUMN name name VARCHAR(50) NOT NULL;`
   - run the insert again, then the `SELECT`

   Record what you saw in `PREDICT.md`. Run `SHOW VARIABLES LIKE 'innodb_autoinc_lock_mode';` too.
6. In `db/iceadmin/u4_03_vendor.sql`, write the steps that succeed: create the table, widen `name` to `VARCHAR(50)`, and insert the four vendors. Leave out the failing insert, because `./sql rebuild` stops at the first error.
7. Run `./sql rebuild iceadmin`, then `SELECT id, name FROM Vendor;` again. The ids are now 1 to 4. The rebuild replaced the database you changed by hand in step 5, and this time no insert failed first.
8. Run `./sql check m4u4-cp03`.

## Done when

- [ ] `./sql check m4u4-cp03` passes.
- [ ] You can explain why the ids started at 5 in step 5, and why they start at 1 after the rebuild.

## Previous / Next

Previous: [02 LeaveBooking](../02-leavebooking/README.md). Next: [04 TRUNCATE and DROP](../04-truncate-vs-drop/README.md).
