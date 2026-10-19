# Notes: 2.4 TV studio

## Decisions

- Station is an entity. The deck's sample stores a nullable `channel VARCHAR` on Transmission instead, which allows misspelt duplicates ("TVM" and "T.V.M.") and transmissions with no station. The scenario says the station must be recorded, so `stationId` is `NOT NULL`.
- The deck's sample has a typo, `VARHCHAR`, which MariaDB rejects. Using `VARCHAR` fixes it.
- Transmission links Episode and Station, but it is not a plain linking table. The same episode legitimately goes out on the same station several times, so `UNIQUE (episodeId, stationId)` would be wrong. What must be unique is the slot: one station can't transmit two things at the same moment. Hence `UNIQUE (stationId, airDateTime)`.
- SeriesActor is a plain linking table, with `UNIQUE (seriesId, actorId)`.
- One director per episode, as the scenario implies. `UNIQUE (seriesId, season, number)` stops two "S1E1" episodes in the same series.
- `season` and `number` are not in the scenario. They are reasonable assumptions, and the deck's sample includes them.

## Acceptable alternatives

- An EpisodeDirector linking table, for episodes with co-directors. Accept it with a note.
- A single Person entity used for both actors and directors, since one person can be both. This is a good design if justified.
- Season as an entity (Series, then Season, then Episode). Accept it.
- Leaving out the slot uniqueness on Transmission. It is not required by the scenario, but worth a comment when marking.

## Watch for

- Station kept as free text on Transmission.
- `UNIQUE (episodeId, stationId)` on Transmission, which blocks legitimate repeats.
- Directors linked to Series instead of Episode.
