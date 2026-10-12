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
