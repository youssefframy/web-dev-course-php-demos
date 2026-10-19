# Checkpoint 05: Referential actions

## Goal

Let Lucy leave the company. Change what each foreign key does when an employee is deleted or their id changes, then delete her.

## Concepts

A foreign key keeps the data consistent. By default it refuses any change that would leave a child row pointing at nothing. Deleting Lucy fails with error 1451, because rows in other tables refer to her.

Refusing is only the default. Each foreign key can say what happens `ON DELETE` and `ON UPDATE` of the row it points at:

| Action | What happens to the child rows |
|---|---|
| `RESTRICT` | Nothing. The change to the parent is refused. This is the default. |
| `NO ACTION` | In InnoDB, the same as `RESTRICT`. Other database systems can check it later, at the end of the statement. |
| `CASCADE` | Delete: the child rows are deleted too. Update: they follow the new id. |
| `SET NULL` | The child column becomes `NULL`. The column must allow `NULL`, or the constraint cannot be created. |
| `SET DEFAULT` | InnoDB accepts the words but stores `RESTRICT`. Do not use it. |

A constraint cannot be edited, so each change is a drop followed by an add. The constraint keeps its name.

If you add `ON DELETE SET NULL` to a `NOT NULL` column, MariaDB answers with error 1005: "Foreign key constraint is incorrectly formed". The real problem is the `NOT NULL`.

## Before you start

This checkpoint expects iceadmin after checkpoint 03. Checkpoint 04 changed nothing. Run `./sql rebuild iceadmin`.

## Steps

1. Break it. In `answers/delete_lucy.sql`, delete employee 2. Run `./sql check m4u4-05-breakit-delete` and read which constraint stopped you.
2. Decide the rules. These four come from the slides, plus LeaveBooking:

   | Constraint | Table | ON UPDATE | ON DELETE | Why |
   |---|---|---|---|---|
   | `c_salary_employee` | Salary | CASCADE | CASCADE | A salary record means nothing without its employee. |
   | `c_employeedepartment_employee` | EmployeeDepartment | CASCADE | CASCADE | Nor does a department link. |
   | `c_vendor_employee` | Vendor | CASCADE | SET NULL | The vendor stays. It has no brand manager until someone replaces Lucy. |
   | `c_leavebooking_employee` | LeaveBooking | CASCADE | CASCADE | Leave bookings belong to the employee. |

   The slides leave out LeaveBooking. Without it, Lucy's two bookings still block the delete. `c_employeedepartment_department` stays as it is, so a department in use cannot be deleted.

3. In `db/iceadmin/u4_05_referential_actions.sql`:
   - Allow `NULL` in `Vendor.brandManager`.
   - Drop and re-add each of the four constraints with its new rules.
   - Finally, delete employee 2.

   This file is part of the chain, so Lucy stays deleted from here on, including in Unit 5.
4. Run `./sql rebuild iceadmin`. Then look at Employee, Salary, EmployeeDepartment, LeaveBooking and Vendor in `./sql shell iceadmin`. Lucy's salaries, department link and leave are gone. Digital Marketing Institute has no brand manager.
5. Changing an id. In `answers/on_update.sql`, change Fred's id from 3 to 30. This runs on a scratch copy, so your Fred keeps id 3. Run `./sql check m4u4-05-on-update`. The check confirms that every table now points at 30.
6. Run `./sql check m4u4-cp05`.

## Done when

- [ ] `./sql check m4u4-cp05` passes.
- [ ] You can say which rows disappeared when Lucy was deleted, and which one was kept with a `NULL`.

## Previous / Next

Previous: [04 TRUNCATE and DROP](../04-truncate-vs-drop/README.md). Next: [06 CHECK constraints](../06-check-constraints/README.md).
