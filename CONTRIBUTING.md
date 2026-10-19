# Contributing (instructors)

## Layout

```
modules/mN-slug/                     module README: overview and links to units
  uN-slug/                           unit README: overview and checkpoint table
    checkpoints/NN-slug/             one checkpoint
```

Use two-digit checkpoint numbers. Slugs are lower case and hyphenated. Exercise folders inside a checkpoint keep the deck's exercise number, for example `2.3-gallery/`.

When you add a unit, add a row to the table in the root `README.md` and link it from the module README.

## Checkpoint README

Every checkpoint `README.md` has these sections, in this order:

1. Goal: one or two sentences.
2. Concepts: brief and in our own words. Never paste slide text.
3. Steps: numbered.
4. Done when: a checklist.
5. Previous / Next: links.

A checkpoint must work on its own. If it depends on earlier work, say what that is and give the student a way to start without it, such as a copy of the file they need.

## SQL files

- Write for MariaDB. Mention MySQL only when noting compatibility.
- Store money as `DECIMAL(10,2)`, never `FLOAT`.
- Every linking table gets `UNIQUE (fk1, fk2)`.
- A student stub is a `schema.sql` containing only a header comment. CI skips stubs and validates them once filled in.
- A `seed.sql` next to a `schema.sql` is applied after it, both locally and in CI.

Run `scripts/validate.sh` before committing.

## Checked checkpoints (Unit 4 onwards)

A checked checkpoint has an `exercises.json` next to its README. `./sql` finds every one under `modules/` and runs them in path order.

```json
{
  "checkpoint": "m4u4-cp02",
  "title": "LeaveBooking",
  "exercises": [
    {"id": "m4u4-02-breakit-fk", "type": "error", "db": "iceadmin", "file": "answers/break_fk.sql", "expect": 1452, "hint": "..."}
  ]
}
```

- `file` is relative to the checkpoint folder. A leading `/` makes it relative to the repository root, for example `/db/iceadmin/u4_02_leavebooking.sql`.
- `db` is the database the check runs against. The scratch copy is built from the chain, up to the end of the exercise's checkpoint or up to `after` (a checkpoint id). If the file is a chain step, the copy stops just before that step.
- Types:
  - `query`: one SELECT. `hash` and `rows` come from `./sql hashgen`. Use `"reference": ""` instead, to compare live against the reference query on the student's own data. Options: `ordered`, `columns`, and `setup` (SQL run on the scratch copy first).
  - `structure` and `script`: run the file, if there is one, then each entry in `assert`. Options: `twice` (run the file twice) and `forbid` (regular expressions the file must not match).
  - `error`: passes when the file stops with an error code in `expect`.
  - `manual`: passes when every box in the Markdown file is ticked.
- Assertions: `table`, `no_table`, `column` (with `type` and `null`), `no_column`, `fk` (`"T.c -> R.c"` with `update` and `delete`), `no_fk`, `unique`, `check` (with `any_of`), `sql` (with `equals`), `rows_unchanged`, and `drift` (a DBML file).
- Break-it files are never chain steps. Chain steps live in `db/<db>/` and are listed in `course/chain.json`.
- Every checkpoint README says which database state it expects, and how to load it with `./sql reset`.

Instructor commands need the solutions repository:

```sh
SOLUTIONS=../ice-bed-solutions ./sql hashgen              # fill hash, rows and reference in every exercises.json
SOLUTIONS=../ice-bed-solutions ./sql snapshot iceadmin m4u4
SOLUTIONS=../ice-bed-solutions ./sql continuity           # what the solutions CI runs
./sql autograding                                         # regenerate .github/classroom/autograding.json
```

After changing a chain step, regenerate the snapshots that follow it and run `continuity`. Update `course/MAP.md` whenever a checkpoint changes a table.

## Solutions

Solutions never go in this repository. They live in the private `ice-bed-solutions` repository, which mirrors these paths. Each solution has a Mermaid diagram, a `schema.sql` that passes `scripts/validate.sh`, and a `NOTES.md` that covers design decisions and acceptable alternatives. Validate them with:

```sh
scripts/validate.sh ../ice-bed-solutions/modules
```

## Commits and tags

Commit each checkpoint separately as `mNuN: checkpoint NN <title>` and tag it `mNuN-cpNN`, for example `m4u2-cp03`.

## Style

- British English.
- Plain READMEs: no emojis, no badges, minimal bold, short sentences.
- One clear way to do each thing. Do not offer lists of alternative tools.
