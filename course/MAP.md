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
| m4u4-cp03 | iceadmin | Vendor created, name widened to VARCHAR(50), 4 rows (ids 1 to 4 after a rebuild) | `db/iceadmin/u4_03_vendor.sql` |
| m4u4-cp04 | none | Scratch copies only; nothing in the chain | none |
| m4u4-cp05 | iceadmin | Salary, EmployeeDepartment, Vendor, LeaveBooking: new ON UPDATE / ON DELETE rules; Vendor.brandManager allows NULL; Lucy (id 2) deleted | `db/iceadmin/u4_05_referential_actions.sql` |
| m4u4-cp06 | iceadmin | LeaveBooking: CHECK c_leavebooking_dates; Salary: CHECK c_salary_amount | `db/iceadmin/u4_06_check_constraints.sql` |
| m4u4-cp07 | tvstudio | Episode: runtimeMinutes, ageRating added, directorId allows NULL; Station.name VARCHAR(100); SET NULL and CASCADE rules on Episode, SeriesActor, Transmission | `db/tvstudio/u4_migration.sql` |
| m4u4-cp08 | iceadmin, tvstudio | No tables; `iceadmin.dbml` and `tvstudio.dbml` brought up to date | `db/*/*.dbml` (not chain steps) |
| m4u5-cp01 | pos | No tables; `pos.dbml` generated with sql2dbml | `db/pos/pos.dbml` (not a chain step) |
| m4u5-cp02 to cp07 | pos, joinlab, iceadmin | None. Queries only. Some checks add rows to their scratch copy | none |
| m4u5-cp08 | tvstudio | Series, Director, Actor, Station, Episode, SeriesActor, Transmission: edge-case rows added (provided, idempotent) | `db/tvstudio/tvstudio-topup.sql` |
| m4u6-cp01 to cp04 | pos | None in the chain. Checks create views and add rows on scratch copies; cp03 and cp04 add the cp02 rows (`u6-further-sql/part2-data.sql`) in `setup` | none |

## Snapshots

| Snapshot | Load with |
|---|---|
| `snapshots/iceadmin/after-m4u3.sql` | `./sql reset iceadmin --at m4u3` |
| `snapshots/tvstudio/after-m4u3.sql` | `./sql reset tvstudio --at m4u3` |
| `snapshots/iceadmin/after-m4u4.sql` | `./sql reset iceadmin --at m4u4` |
| `snapshots/tvstudio/after-m4u4.sql` | `./sql reset tvstudio --at m4u4` |
| `snapshots/iceadmin/after-m4u5.sql` | `./sql reset iceadmin --at m4u5` (the same as after-m4u4: Unit 5 only reads iceadmin) |
| `snapshots/tvstudio/after-m4u5.sql` | `./sql reset tvstudio --at m4u5` |
| `seeds/pos.sql` | `./sql reset pos` |
| `seeds/joinlab.sql` | `./sql reset joinlab` |
| `seeds/pos-demo.sql` | Extra POS rows for demonstrations: `./sql shell pos < seeds/pos-demo.sql`. Never used by the checks |

## Decisions

- TODO: Unit 3 has a presenter demo but no checkpoints yet. Until it does, the `after-m4u3` snapshots are written by hand. The iceadmin one comes from the U3 demo, and the tvstudio one from the Exercise 2.4 reference design. When U3 gets checkpoints, its DDL and DML files become the first chain steps.
- The database is `iceadmin`, in lower case. The Unit 3 demo uses `IceAdmin`. Database names are case-sensitive on the Linux server, so the two are separate databases.
- `after-m4u3` stores money as `DECIMAL(10,2)` and gives EmployeeDepartment `UNIQUE (employeeId, departmentId)`. The U3 slides use `FLOAT` and leave the pair unconstrained.
- Unit 2's checkpoint 07 also builds an `iceadmin`, with different data. `./sql reset iceadmin` replaces it.
- People's names follow Unit 3, because the chain starts there: Joe Mifsud, Lucy Marconi and Fred Wake. The Unit 4 slides show Joe Doe and Lucy Pace.
- Unit 4 checkpoint 02 seeds LeaveBooking with the slides' two bookings for Lucy, plus three for Joe and Fred. Lucy's bookings are deleted with her in checkpoint 05, and Unit 5 still needs leave data. The slides' bad booking uses employee 3, who is Fred in iceadmin. The break-it uses employee 4 instead.
- Unit 4 checkpoint 05 adds a fourth rule the slides leave out: `c_leavebooking_employee ON UPDATE CASCADE ON DELETE CASCADE`. Without it, Lucy's leave bookings block the delete.
- The Unit 4 demonstrations that would change ids or remove the foreign key (ON UPDATE CASCADE, and dropping `c_leavebooking_employee`) run on scratch copies, so the chain is not affected.
- Vendor ids are 1 to 4 after a rebuild. A student who ran the failing insert by hand sees 5 to 8 until they rebuild. No later query depends on Vendor ids.
- Unit 5 uses U4's column names, `dateFrom` and `dateTo`. The U5 slides' sub-query uses `fromDate` and `toDate`.
- Unit 6 changes the student's own `pos` (views, plus the checkpoint 02 invoices and payment), but every check starts from `seeds/pos.sql` and rebuilds what it needs in `setup`. So pos has no chain steps and no `m4u6` snapshot. `part2-data.sql` restores the checkpoint 02 rows after a reset, with the workbook's ids (invoices 6 and 7).
- `seeds/pos.sql` keeps the gist's data exactly, including the typos "Forkes" and "Refridgeration", so results match the slides.
