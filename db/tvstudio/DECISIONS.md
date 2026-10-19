# tvstudio: referential actions

One row per foreign key in tvstudio. Say what happens when the parent row is deleted or its id changes, and why.

| Foreign key | ON DELETE | ON UPDATE | Why |
|---|---|---|---|
| Episode.seriesId -> Series.id | | | |
| Episode.directorId -> Director.id | | | |
| SeriesActor.seriesId -> Series.id | | | |
| SeriesActor.actorId -> Actor.id | | | |
| Transmission.episodeId -> Episode.id | | | |
| Transmission.stationId -> Station.id | | | |

- [ ] Every row has an action and a reason.
- [ ] For the two foreign keys the brief does not mention, I wrote down who I would ask to confirm the choice.
