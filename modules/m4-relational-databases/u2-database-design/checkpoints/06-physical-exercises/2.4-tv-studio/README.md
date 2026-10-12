# Exercise 2.4: TV studio

Build a physical ERD in ERD Editor for this scenario and export it to [schema.sql](schema.sql).

## Scenario

- A TV studio wants a database of the TV series it produces.
- For each series, it stores the actors who appear in it and the directors who direct its episodes.
- Actors may appear in several series. Directors may direct several episodes, across different series.
- Each series is divided into episodes. An episode may be transmitted by several TV stations, several times each. The station and the date and time of each transmission must be recorded.

## Requirement checklist

- [ ] Series, with a name.
- [ ] Actor, linked to series through a linking table.
- [ ] Episode, belonging to exactly one series.
- [ ] Director, linked to the episodes they direct.
- [ ] Station is an entity of its own, with a name.
- [ ] Transmission records one episode, one station, and the date and time it went out.
- [ ] Every linking table has a unique index on its two foreign keys.
- [ ] `scripts/validate.sh` passes.

Back to [checkpoint 06](../README.md).
