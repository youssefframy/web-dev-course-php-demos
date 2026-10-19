# Checkpoint 03: predictions

Answer each question before step 5. Then fill in what happened and tick the box.

## 1. After the failed insert

The error says the problem is at row 2. Microsoft, in row 1, fits. How many rows will `SELECT * FROM Vendor;` show after the failed insert?

Prediction:

What happened:

- [ ] I predicted, then checked with `SELECT * FROM Vendor;`.

## 2. The ids after the second insert

After you widen `name` and insert the four vendors again, what id will Microsoft get?

Prediction:

What happened (including the value of `innodb_autoinc_lock_mode`):

- [ ] I predicted, then checked with `SELECT id, name FROM Vendor;`.
