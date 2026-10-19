# Checkpoint 07: tvstudio evolves

## Goal

Change your tvstudio database to meet a new set of requirements, using `ALTER TABLE` only, without losing a row.

## Concepts

A real database cannot be dropped and created again, because it is full of data people need. Every change is a migration: a script of `ALTER TABLE` statements that takes the database from one version to the next. The rows stay.

Some changes need a step first. A foreign key can only use `SET NULL` if its column allows `NULL`, so the column changes before the constraint does.

A referential action is a business decision, not a technical one. Each one needs a reason someone at the studio would agree with.

## Before you start

This checkpoint expects tvstudio as it was at the end of Unit 3. To load it, run:

```sh
./sql reset tvstudio --at m4u3
```

The checks use the table and column names of the Exercise 2.4 reference design: Series, Actor, SeriesActor, Director, Episode, Station and Transmission. `m4u4-07-preflight` confirms your database has them.

## Change brief

From the studio's head of scheduling:

1. Episodes need a running time and an age rating. Store the running time in whole minutes as `runtimeMinutes SMALLINT UNSIGNED`. Store the rating as `ageRating ENUM('U', 'PG', '12', '15', '18')`. Existing episodes have neither yet, so both columns allow `NULL`.
2. We are adding stations with longer names. Station names must hold up to 100 characters. Station names are still required and still unique.
3. When a director leaves and is deleted, their episodes stay. The episode's director becomes unknown (`NULL`).
4. When a series is cancelled and deleted, everything that belongs to it goes: its episodes, its cast links in SeriesActor, and the transmissions of those episodes.

## Steps

1. Run `./sql check m4u4-07-preflight`.
2. Find the names of your foreign keys. You will need them to drop the constraints:

   ```sh
   echo "SHOW CREATE TABLE Episode;" | ./sql shell tvstudio
   ```

3. Write `db/tvstudio/u4_migration.sql`. Use `ALTER TABLE` only. No `CREATE TABLE`, `DROP TABLE`, `INSERT`, `UPDATE` or `DELETE`.
4. Run `./sql rebuild tvstudio`. If a statement fails, the rebuild stops and names it. Fix the file and run the rebuild again.
5. Fill in `db/tvstudio/DECISIONS.md`: one row per foreign key in tvstudio, with the action you chose and why. That includes the foreign keys the brief does not mention.
6. Run `./sql check m4u4-cp07`. The checks run your migration on a scratch copy. Then they delete a test director and a test series, to confirm the database behaves as the brief says.

## Done when

- [ ] `./sql check m4u4-cp07` passes.
- [ ] Every row count in tvstudio is the same before and after your migration.
- [ ] `DECISIONS.md` gives a reason for every foreign key.

## Previous / Next

Previous: [06 CHECK constraints](../06-check-constraints/README.md). Next: [08 Diagrams in sync](../08-sync/README.md).
