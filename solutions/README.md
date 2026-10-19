# Solutions

Reference answers for every checkpoint. Each file is at the same path as the file you work on, under `solutions/`. For example:

| Your file | The reference answer |
|---|---|
| `db/iceadmin/u4_02_leavebooking.sql` | `solutions/db/iceadmin/u4_02_leavebooking.sql` |
| `modules/.../03-aggregates-group-by/answers/average_invoice.sql` | `solutions/modules/.../03-aggregates-group-by/answers/average_invoice.sql` |

Try each exercise yourself first. Use the answers when you are stuck, or to compare once your own check passes. There is often more than one correct query. `./sql check` marks your own files, so a different query that gives the same result also passes.

Some folders also have a `NOTES.md` or a `PREDICT.md`. These explain design choices, give acceptable alternatives, and say what the server actually did when the prediction exercises were run.

To see every reference answer pass against your own databases:

```sh
SOLUTIONS=solutions ./sql check --all
```
