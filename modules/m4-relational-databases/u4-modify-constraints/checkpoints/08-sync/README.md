# Checkpoint 08: Diagrams in sync

## Goal

Bring the diagrams of iceadmin and tvstudio up to date with everything this unit changed, and prove they match the databases.

## Concepts

A diagram is only useful if it matches the database. Every time a checkpoint changes a structure, the `.dbml` file that describes it must change too. This checkpoint catches up on the whole unit.

`./sql drift <db>` turns your DBML into SQL with `dbml2sql --mysql`, applies it to an empty scratch database, and compares the result with your database. It compares:

- tables and columns: type, `NULL`, default and `AUTO_INCREMENT`
- primary keys and unique indexes
- foreign keys, with their `ON UPDATE` and `ON DELETE` rules
- `CHECK` constraints

Constraint names, column order, notes and plain indexes are not compared.

The DBML you need for this unit:

```
Ref c_vendor_employee: Vendor.brandManager > Employee.id [update: cascade, delete: set null]

Table LeaveBooking {
  ...
  checks {
    `dateTo >= dateFrom` [name: 'c_leavebooking_dates']
  }
}

Enum age_rating {
  U
  PG
  "12"
}
```

Values that start with a digit, such as `"12"`, need quotes in a DBML enum. A type with a space in it, such as `SMALLINT UNSIGNED`, needs double quotes too: `runtimeMinutes "SMALLINT UNSIGNED" [null]`.

## Before you start

This checkpoint expects both databases at the end of Unit 4. Run `./sql rebuild iceadmin` and `./sql rebuild tvstudio`.

## Steps

1. Run `./sql drift iceadmin`. Each line names something that is in the database but not in `db/iceadmin/iceadmin.dbml`, or the other way round.
2. Edit `db/iceadmin/iceadmin.dbml` until `./sql drift iceadmin` passes. You need:
   - the LeaveBooking and Vendor tables
   - both CHECK constraints
   - the new rules on four foreign keys
3. Paste the file into <https://dbdiagram.io> and look at the diagram.
4. Do the same for tvstudio: `./sql drift tvstudio`, then edit `db/tvstudio/tvstudio.dbml`.
5. Run `./sql check m4u4-cp08`. Then run `./sql check m4u4-cp01` to `m4u4-cp08` to confirm the whole unit passes.

## Done when

- [ ] `./sql drift iceadmin` and `./sql drift tvstudio` both pass.
- [ ] `./sql check m4u4-cp08` passes.
- [ ] `./sql rebuild iceadmin` and `./sql rebuild tvstudio` both run every Unit 4 step. Unit 5 starts from here.

## Previous / Next

Previous: [07 tvstudio evolves](../07-tvstudio-evolves/README.md). Next: [Unit 5](../../../u5-queries/README.md).
