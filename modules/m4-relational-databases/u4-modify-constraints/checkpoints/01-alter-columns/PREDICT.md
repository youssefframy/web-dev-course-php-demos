# Checkpoint 01: predictions

Write each prediction before you run anything. Then run it, write what happened, and tick the box.

## 1. A NOT NULL DATETIME column on a table with rows

Employee has three rows. You add `employmentStart DATETIME NOT NULL`, with no default. Will the ALTER fail? If not, what will the three existing rows hold in the new column?

Prediction:

What happened:

- [ ] I predicted, ran `u4_01_add_column.sql`, and wrote down what the rows hold.

## 2. Changing the column to DATE

What happens to those values when the column changes to `DATE NOT NULL`?

Prediction:

What happened:

- [ ] I predicted, ran `u4_01_change_column.sql`, and wrote down what the rows hold.

## 3. Why this matters

In one or two sentences: why is the value you saw in question 1 a poor start date for a real employee, and what would you add to the column definition instead?

Answer:

- [ ] I answered question 3.
