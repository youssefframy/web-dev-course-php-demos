# Checkpoint 01: predictions

Write each prediction before you run anything. Then run it, write what happened, and tick the box.

## 1. A NOT NULL DATETIME column on a table with rows

Employee has three rows. You add `employmentStart DATETIME NOT NULL`, with no default. Will the ALTER fail? If not, what will the three existing rows hold in the new column?

Prediction: (the student's own)

What happened: observed on mariadb:11.4.13 with the course sql_mode. The ALTER succeeds, and every existing row gets the zero date `0000-00-00 00:00:00`. MariaDB uses the type's implicit default because the column has no `DEFAULT`. The course sql_mode does not include `NO_ZERO_DATE`. With `NO_ZERO_DATE` added, the same ALTER fails with error 1292 (Incorrect datetime value: '0000-00-00 00:00:00').

- [x] I predicted, ran `u4_01_add_column.sql`, and wrote down what the rows hold.

## 2. Changing the column to DATE

What happens to those values when the column changes to `DATE NOT NULL`?

Prediction: (the student's own)

What happened: the values become `0000-00-00`. The time part is dropped. The ALTER succeeds.

- [x] I predicted, ran `u4_01_change_column.sql`, and wrote down what the rows hold.

## 3. Why this matters

In one or two sentences: why is the value you saw in question 1 a poor start date for a real employee, and what would you add to the column definition instead?

Answer: a zero date is not a real date, and date functions and comparisons treat it unpredictably. Allow NULL until the real date is known, or give the column a sensible DEFAULT and then UPDATE the rows with real values.

- [x] I answered question 3.

Marking: accept any prediction. The "What happened" answers must describe the zero dates.
