# tvstudio: referential actions

One row per foreign key in tvstudio. Say what happens when the parent row is deleted or its id changes, and why.

| Foreign key | ON DELETE | ON UPDATE | Why |
|---|---|---|---|
| Episode.seriesId -> Series.id | CASCADE | CASCADE | Brief, point 4. An episode cannot exist without its series. |
| Episode.directorId -> Director.id | SET NULL | CASCADE | Brief, point 3. The episode was made and still airs. Only the credit is lost. |
| SeriesActor.seriesId -> Series.id | CASCADE | CASCADE | Brief, point 4. A cast link to a deleted series means nothing. |
| SeriesActor.actorId -> Actor.id | RESTRICT | CASCADE | Not in the brief. Deleting an actor who is in the cast of a series is more likely a mistake than a decision, so refuse it. |
| Transmission.episodeId -> Episode.id | CASCADE | CASCADE | Brief, point 4: the transmissions of a deleted series go too. They reach Transmission through Episode. |
| Transmission.stationId -> Station.id | RESTRICT | CASCADE | Not in the brief. The schedule is a record of what was broadcast, so a station that has broadcast cannot be deleted. |

- [x] Every row has an action and a reason.
- [x] For the two foreign keys the brief does not mention, I wrote down who I would ask to confirm the choice.

Marking: the four actions in the brief are fixed. For SeriesActor.actorId and Transmission.stationId, accept RESTRICT or CASCADE if the reason is sound. SET NULL is not possible on those NOT NULL columns. ON UPDATE CASCADE is the usual choice. Accept RESTRICT with a reason. This answer leaves the two foreign keys outside the brief on their defaults, which is RESTRICT for both rules. The "who I would ask" box: accept the head of scheduling, or the studio's archive or legal team.
