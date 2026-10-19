-- Unit 5, checkpoint 08, q2. Write one SELECT statement below.
SELECT Actor.name, Actor.surname, COUNT(SeriesActor.seriesId) AS series
FROM Actor
    JOIN SeriesActor ON SeriesActor.actorId = Actor.id
GROUP BY Actor.id, Actor.name, Actor.surname
HAVING COUNT(SeriesActor.seriesId) > 1;
