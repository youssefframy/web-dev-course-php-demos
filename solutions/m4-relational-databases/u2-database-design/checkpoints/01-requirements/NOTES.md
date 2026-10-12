# Notes: 01 Requirements

## Decisions

- Salary is an entity, not an attribute of Employee, because past salaries must be kept. An attribute could hold only one value.
- "Past salaries" is not a separate entity. A past salary is simply a Salary whose `toDate` has been set.
- Employee to Department is many-to-many. Students should state it here. Resolving it is checkpoint 03's job.

## Acceptable alternatives

- Department side minimum of one ("one or many employees") instead of zero. Accept it if the student explains why.
- Extra employee attributes (email, date of birth, employee number), as long as they are listed as assumptions.
- A `basis` (full-time or part-time) attribute. It appears in the physical design later.

## Watch for

- Treating "ICE Malta" or "system" as an entity.
- A single `salary` attribute on Employee, which loses the history.
- No multiplicity on one side of a relationship.
