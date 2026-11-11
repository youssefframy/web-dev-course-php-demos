# Checkpoint 09: Self-study

## Goal

Use what you have learnt in this unit, without step-by-step instructions, to make StudentDB sort in both directions and show which way it is sorted. The extra credit adds a search.

## Concepts

Nothing new. You need:

- `saveValue()` and `loadValue()` from checkpoint 08
- the comparison functions from checkpoint 05, and `<=>`
- `array_filter()` from checkpoint 06, and `str_contains()`. For the extra credit also `strtolower()`
- the Bootstrap icons `bi-caret-up-fill` and `bi-caret-down-fill`, which the starter already loads

## Before you start

You need StudentDB from checkpoint 08. If you skipped it, copy the reference answer over yours:

```sh
cp -R solutions/modules/m5-php/u6-working-with-arrays/checkpoints/08-studentdb/studentdb/. modules/m5-php/u6-working-with-arrays/studentdb/
```

Start the server in `modules/m5-php/u6-working-with-arrays/studentdb`.

## Steps

1. Make the sort direction a choice. Clicking a header that is already the sort column flips between ascending and descending. Clicking a different header sorts it ascending. The ID column must work too.
2. Save the direction like the column, so it survives a refresh. Only `asc` and `desc` are valid, so decide what to do with anything else.
3. Show a caret that points up for ascending and down for descending, in the header of the sorted column only.
4. Check all four columns in both directions, then add a student and check the order is kept.
5. Extra credit: add a search box above the table, showing only the students whose name, surname or grade contains the text, ignoring case.
   - The saved list must not change. Clearing the search brings everyone back.
   - The ids and delete links of the students shown must still refer to the right students.
   - Sorting and deleting must not drop the search.

## Done when

- [ ] Clicking the same header twice reverses the order, and a caret shows which way.
- [ ] The direction survives a refresh and a browser restart.
- [ ] Extra credit: a search shows only the matching students, and sorting and deleting keep the search.
- [ ] Your work is committed and pushed (see [How to submit](../../../../../README.md#how-to-submit)).

## Previous / Next

Previous: [08 StudentDB](../08-studentdb/README.md). Next: Unit 7, coming soon.
