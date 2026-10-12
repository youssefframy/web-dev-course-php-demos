# Checkpoint 07: Implement and validate

## Goal

Build ICEAdmin in MariaDB, load the simulated data, and prove with queries that the relationships work.

## Concepts

A design is only finished once real data fits it. Loading the data from checkpoint 03 tests three things:

- The schema is valid SQL that MariaDB accepts.
- The data fits the types and constraints. Dates must be `YYYY-MM-DD`, and amounts must fit `DECIMAL(10,2)`.
- The relationships answer real questions, such as "which departments does Lucy work in?"

Order matters when you load data. A foreign key must point at a row that already exists, so departments and employees go in before the salaries and links that refer to them.

Constraints are the database protecting itself. The unique index, the foreign keys and the `ENUM` all reject bad rows, whichever program tries to insert them.

### Files in this folder

| File | Contents |
|---|---|
| `schema.sql` | The ICEAdmin tables. A copy of the checkpoint 05 reference, so you can start here. |
| `seed.sql` | The simulated data from checkpoint 03. |
| `checks.sql` | Queries with their expected results, plus three inserts that should fail. |

### How your work is validated

Every time you push, GitHub runs the same check as `scripts/validate.sh`. You can see it under the Actions tab of your repository. For each non-empty `schema.sql` in the repository, it:

1. creates a fresh, empty database
2. applies the `schema.sql`
3. applies the `seed.sql` in the same folder, if there is one.

If any step fails, the run fails and names the file, along with MariaDB's error message. Stub files that still contain only their header comment are skipped. So your exports from checkpoints 05 and 06 are checked automatically as soon as you commit them.

## Steps

Run these from the repository root.

1. Start the database:

   ```sh
   docker compose up -d --wait
   ```

2. Create an empty database for ICEAdmin:

   ```sh
   echo "DROP DATABASE IF EXISTS iceadmin; CREATE DATABASE iceadmin;" | scripts/sql.sh
   ```

3. Apply the schema, then the data:

   ```sh
   scripts/sql.sh iceadmin < modules/m4-relational-databases/u2-database-design/checkpoints/07-implement-and-validate/schema.sql
   scripts/sql.sh iceadmin < modules/m4-relational-databases/u2-database-design/checkpoints/07-implement-and-validate/seed.sql
   ```

4. Run the checks and compare each result with the "Expected" comment above its query:

   ```sh
   scripts/sql.sh -t iceadmin < modules/m4-relational-databases/u2-database-design/checkpoints/07-implement-and-validate/checks.sql
   ```

5. Open `checks.sql`. Uncomment one of the three inserts at the bottom, run the file again and read the error. Put the comment back before trying the next one. Each error names the constraint that stopped the insert.
6. Open Adminer at <http://localhost:8080> (server `db`, user `root`, password `root`) and browse the `iceadmin` tables to see the same data.
7. Run `scripts/validate.sh`, then commit and push. Check that the Actions run on GitHub is green.

## Done when

- [ ] The `iceadmin` database exists and contains the seed data.
- [ ] All five check queries return their expected results.
- [ ] You have seen each of the three inserts rejected, and you can say which constraint stopped each one.
- [ ] `scripts/validate.sh` passes, and so does the Actions run on GitHub.

## Previous / Next

Previous: [06 Physical design exercises](../06-physical-exercises/README.md). Next: back to the [unit overview](../../README.md).
