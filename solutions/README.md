# ICE Malta BED: solutions (private)

Instructor solutions for the exercises repository. Keep this repository private.

Paths mirror the exercises repository. Each solution folder contains:

- a Mermaid diagram (`diagram.md`, or the file name the exercise asks for)
- `schema.sql`, which passes validation, where the exercise has a physical form
- `NOTES.md`, covering design decisions and acceptable alternatives to accept when marking

Validate every schema from the exercises repository, with its database running:

```sh
scripts/validate.sh solutions
```

Checkpoint 03 has no separate solution, because the README itself walks through the answer. Checkpoints 05 and 07 use the reference files that ship with the exercises.
