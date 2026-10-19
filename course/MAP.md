# Course map

Three databases run through Module 4. Each unit starts where the previous one ended. `chain.json` lists the files that build each database, in order. `./sql rebuild <db>` replays them.

| Database | Role |
|---|---|
| iceadmin | The worked example. |
| tvstudio | Your own build, from Exercise 2.4. |
| pos | Provided in Unit 5. |
| joinlab | A toy database for the Unit 5 join slides. Nothing builds on it. |

## Which checkpoint changes what

| Checkpoint | Database | Tables changed | Chain file |
|---|---|---|---|
| m4u3 (base) | iceadmin | Employee, Department, Salary, EmployeeDepartment created and filled | `snapshots/iceadmin/after-m4u3.sql` |
| m4u3 (base) | tvstudio | Series, Actor, SeriesActor, Director, Episode, Station, Transmission created and filled | `snapshots/tvstudio/after-m4u3.sql` |
| m4u4-cp01 | iceadmin | Employee: employmentStart added, changed, dropped (no net change) | `db/iceadmin/u4_01_*.sql` |
| m4u4-cp02 | iceadmin | LeaveBooking created with 5 rows; c_leavebooking_employee added | `db/iceadmin/u4_02_leavebooking.sql` |

## Snapshots

| Snapshot | Load with |
|---|---|
| `snapshots/iceadmin/after-m4u3.sql` | `./sql reset iceadmin --at m4u3` |
| `snapshots/tvstudio/after-m4u3.sql` | `./sql reset tvstudio --at m4u3` |
| `seeds/pos.sql` | `./sql reset pos` |
| `seeds/joinlab.sql` | `./sql reset joinlab` |

## Decisions

- TODO: Unit 3 has a presenter demo but no checkpoints yet. Until it does, the `after-m4u3` snapshots are written by hand. The iceadmin one comes from the U3 demo, and the tvstudio one from the Exercise 2.4 reference design. When U3 gets checkpoints, its DDL and DML files become the first chain steps.
- The database is `iceadmin`, in lower case. The Unit 3 demo uses `IceAdmin`. Database names are case-sensitive on the Linux server, so the two are separate databases.
- `after-m4u3` stores money as `DECIMAL(10,2)` and gives EmployeeDepartment `UNIQUE (employeeId, departmentId)`. The U3 slides use `FLOAT` and leave the pair unconstrained.
- Unit 2's checkpoint 07 also builds an `iceadmin`, with different data. `./sql reset iceadmin` replaces it.
- People's names follow Unit 3, because the chain starts there: Joe Mifsud, Lucy Marconi and Fred Wake. The Unit 4 slides show Joe Doe and Lucy Pace.
- `seeds/pos.sql` keeps the gist's data exactly, including the typos "Forkes" and "Refridgeration", so results match the slides.
