# Checkpoint 06: Physical design exercises

## Goal

Produce physical ERDs in ERD Editor for two new scenarios, and export each one as SQL that MariaDB accepts.

## Concepts

This checkpoint uses everything from checkpoint 05. Each design needs:

- a primary key on every table, with `AUTO_INCREMENT` on surrogate ids
- every many-to-many relationship resolved with a linking table, and each linking table given a unique index on its two foreign keys
- a data type, `NULL` or `NOT NULL`, and a default where useful, on every column
- money stored as `DECIMAL(10,2)`

Some scenario details are better stored in a different form from the wording. "Age" is a good example. It changes every year, so store a date of birth and work out the age when you need it.

## Steps

For each exercise:

1. Create an `.erd` file in the exercise folder (for example `gallery.erd`) and set its database to MariaDB.
2. Build the design in ERD Editor.
3. Export the SQL DDL over the exercise's `schema.sql`.
4. Run `scripts/validate.sh` from the repository root and fix anything that fails.

Exercises:

- [2.3 Gallery](2.3-gallery/README.md)
- [2.4 TV studio](2.4-tv-studio/README.md)

If you are starting here, read the "Steps" section of [checkpoint 05](../05-physical-erd/README.md) for how to use ERD Editor.

## Done when

- [ ] Both exercise folders contain an `.erd` file and an exported `schema.sql`.
- [ ] `scripts/validate.sh` reports PASS for both.
- [ ] Every item on each exercise's requirement checklist is ticked.

## Previous / Next

Previous: [05 Physical ERD](../05-physical-erd/README.md). Next: [07 Implement and validate](../07-implement-and-validate/README.md).
