# Unit 4: Modifying Structure & Adding Constraints

Databases change after they are built. In this unit you change iceadmin and your own tvstudio with `ALTER TABLE`, and you add the constraints that protect their data.

Constraints are the rules a table enforces. A primary key, `UNIQUE`, `NOT NULL`, `CHECK` and a foreign key are all constraints. A foreign key is the one that links two tables.

## Where you start

This unit starts from iceadmin and tvstudio as they were at the end of Unit 3. To load that state:

```sh
./sql reset iceadmin --at m4u3
./sql reset tvstudio --at m4u3
```

Your work goes in `db/iceadmin/` and `db/tvstudio/`. Each file there is one step in building the database. `./sql rebuild <db>` runs your steps in order, on top of the Unit 3 state. At the end of the unit your databases should match `after-m4u4`, which Unit 5 starts from.

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [Alter columns](checkpoints/01-alter-columns/README.md) | A column added, changed and dropped |

Back to [Module 4](../README.md).
