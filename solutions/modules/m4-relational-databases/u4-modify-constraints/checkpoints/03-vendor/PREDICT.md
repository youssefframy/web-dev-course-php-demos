# Checkpoint 03: predictions

Answer each question before step 5. Then fill in what happened and tick the box.

## 1. After the failed insert

The error says the problem is at row 2. Microsoft, in row 1, fits. How many rows will `SELECT * FROM Vendor;` show after the failed insert?

Prediction: (the student's own)

What happened: observed on mariadb:11.4.13. No rows. The statement is atomic, so Microsoft was not inserted either.

- [x] I predicted, then checked with `SELECT * FROM Vendor;`.

## 2. The ids after the second insert

After you widen `name` and insert the four vendors again, what id will Microsoft get?

Prediction: (the student's own)

What happened (including the value of `innodb_autoinc_lock_mode`): Microsoft got id 5, and the four vendors got 5 to 8. `innodb_autoinc_lock_mode` is 1, so the failed four-row insert reserved ids 1 to 4 before it ran. Note that ALTER TABLE rebuilds the table and keeps the counter at 5.

- [x] I predicted, then checked with `SELECT id, name FROM Vendor;`.

Marking: accept any prediction. "What happened" must say 0 rows, then ids 5 to 8.
