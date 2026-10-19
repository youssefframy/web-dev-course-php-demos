# Checkpoint 08: Querying tvstudio

## Goal

Answer five questions about your own tvstudio database.

## Concepts

This is your database, so nobody else's result is the right answer. The check runs a reference query on your own tvstudio and compares its result with yours. This works because Unit 4 checked that your tables and columns use the Exercise 2.4 names.

Your Unit 3 data may not have the cases these questions are about: a director with no episodes, say. `db/tvstudio/tvstudio-topup.sql` adds them. It is a chain step, so `./sql rebuild tvstudio` runs it after your Unit 4 migration. It only adds rows that are missing, so running it twice changes nothing. It also uses everything your migration added, including a station name longer than 50 characters, so it fails if the migration is missing.

## Before you start

```sh
./sql rebuild tvstudio
```

The rebuild should end at `m4u5-cp08`. If it stops earlier, fix the step it names. If you cannot, run `./sql reset tvstudio --at m4u4` first, then run the top-up with `./sql shell tvstudio < db/tvstudio/tvstudio-topup.sql`.

## Steps

Write each answer in its file under `answers/`, then run `./sql check m4u5-cp08`.

1. `q1_episodes_per_series.sql`: every series with its number of episodes, including series with none.
2. `q2_actors_in_several_series.sql`: every actor cast in more than one series, with the number of series.
3. `q3_directors_without_episodes.sql`: the name and surname of every director who has directed no episodes.
4. `q4_series_never_transmitted.sql`: the name of every series none of whose episodes has ever been transmitted. That includes series with no episodes.
5. `q5_transmissions_per_station_month.sql`: the number of transmissions for each station in each month, with the month as `YYYY-MM`. Order by station name, then month.

## Done when

- [ ] `./sql check m4u5-cp08` passes.
- [ ] `./sql progress` shows Units 4 and 5 complete.

## Previous / Next

Previous: [07 Exercise 5.3](../07-exercises-5-3/README.md). Next: Unit 6.
