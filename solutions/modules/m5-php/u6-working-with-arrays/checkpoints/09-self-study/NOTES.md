# Notes: Unit 6 self-study

The reference answer includes the extra credit. Without it, a student only needs the sort direction and the icon.

## Sort direction

- The direction is saved with `saveValue('sortDir', ...)`, next to `sortBy`, so it survives a refresh and a browser restart.
- `setSort()` flips the direction only when the requested column is already the active one. Choosing a different column starts at ascending.
- `uasort()` multiplies the result of `<=>` by `1` or `-1`. Swapping the operands, `$b[$sortBy] <=> $a[$sortBy]`, or negating with `-($a <=> $b)`, is just as good. For the ID column, `ksort()` and `krsort()` are used.
- `array_reverse($studentList, true)` after sorting also works, as long as the second argument, `true`, keeps the keys. Without it the ids are renumbered and the delete links point at the wrong students.
- The icon is `bi-caret-up-fill` or `bi-caret-down-fill`, shown only in the active column's header.

## Search

- The search form uses `GET` and a field called `q`, so a result page can be bookmarked and refreshed without the browser asking to send a form again.
- `searchStudents()` returns a new array. The saved list is never changed, so clearing the search brings every student back.
- `array_filter()` keeps the keys, so the ids shown and the delete links still refer to the right students. `array_values()` here would be a bug.
- The name, surname and grade are joined into one lowercase string and checked with `str_contains()`. Checking each field with `stripos()` separately is fine too. Searching for `9` matches any grade containing a 9, which is acceptable for this exercise.
- The search text is added to the sort and delete links, so sorting or deleting does not drop the search.
- The search text is escaped with `htmlspecialchars()` where it is put back into the field.
