-- Unit 5, checkpoint 08: extra rows for the tvstudio questions. Provided; you do not need to edit it.
-- It adds a series with no transmissions, a series with no episodes, a director with no episodes,
-- an actor in two series, and a station whose name is longer than 50 characters.
-- It only adds rows that are not there yet, so running it twice changes nothing.
-- It needs your Unit 4 migration: the long station name, the NULL director and the new Episode columns.

INSERT INTO Series (name)
SELECT v.name
FROM (SELECT 'Night Desk' AS name UNION ALL SELECT 'Coming Soon' UNION ALL SELECT 'Late Show') v
WHERE NOT EXISTS (SELECT 1 FROM Series WHERE Series.name = v.name);

INSERT INTO Director (name, surname)
SELECT v.name, v.surname
FROM (SELECT 'Rita' AS name, 'Azzopardi' AS surname UNION ALL SELECT 'Tom', 'Mallia') v
WHERE NOT EXISTS (SELECT 1 FROM Director WHERE Director.name = v.name AND Director.surname = v.surname);

INSERT INTO Actor (name, surname)
SELECT 'Leah', 'Cassar'
WHERE NOT EXISTS (SELECT 1 FROM Actor WHERE Actor.name = 'Leah' AND Actor.surname = 'Cassar');

INSERT INTO Station (name)
SELECT 'Malta Community Broadcasting Service: Channel Two HD'
WHERE NOT EXISTS (SELECT 1 FROM Station WHERE Station.name = 'Malta Community Broadcasting Service: Channel Two HD');

-- Night Desk is never transmitted. Its second episode has no director yet.
INSERT INTO Episode (season, number, title, seriesId, directorId, runtimeMinutes, ageRating)
SELECT v.season, v.number, v.title, Series.id, Director.id, v.runtimeMinutes, v.ageRating
FROM (
        SELECT 'Night Desk' AS series, 1 AS season, 1 AS number, 'Deadline' AS title, 'Tom' AS director, 50 AS runtimeMinutes, '15' AS ageRating
        UNION ALL SELECT 'Night Desk', 1, 2, 'Off the Record', NULL, 50, '15'
        UNION ALL SELECT 'Late Show', 1, 1, 'Opening Night', 'Tom', 60, 'PG'
    ) v
    JOIN Series ON Series.name = v.series
    LEFT JOIN Director ON Director.name = v.director AND Director.surname = 'Mallia'
WHERE NOT EXISTS (
    SELECT 1 FROM Episode
    WHERE Episode.seriesId = Series.id AND Episode.season = v.season AND Episode.number = v.number
);

-- Leah Cassar is cast in two series.
INSERT INTO SeriesActor (seriesId, actorId)
SELECT Series.id, Actor.id
FROM Series
    JOIN Actor ON Actor.name = 'Leah' AND Actor.surname = 'Cassar'
WHERE Series.name IN ('Night Desk', 'Coming Soon')
    AND NOT EXISTS (SELECT 1 FROM SeriesActor WHERE SeriesActor.seriesId = Series.id AND SeriesActor.actorId = Actor.id);

-- Late Show airs twice on the new station, in two different months.
INSERT INTO Transmission (airDateTime, episodeId, stationId)
SELECT v.airDateTime, Episode.id, Station.id
FROM (SELECT '2024-04-05 22:00:00' AS airDateTime UNION ALL SELECT '2024-05-03 22:00:00') v
    JOIN Series ON Series.name = 'Late Show'
    JOIN Episode ON Episode.seriesId = Series.id AND Episode.season = 1 AND Episode.number = 1
    JOIN Station ON Station.name = 'Malta Community Broadcasting Service: Channel Two HD'
WHERE NOT EXISTS (
    SELECT 1 FROM Transmission
    WHERE Transmission.stationId = Station.id AND Transmission.airDateTime = v.airDateTime
);
