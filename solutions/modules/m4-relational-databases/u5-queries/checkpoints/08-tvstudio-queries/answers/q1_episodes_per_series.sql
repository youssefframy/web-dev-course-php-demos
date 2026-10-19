-- Unit 5, checkpoint 08, q1. Write one SELECT statement below.
SELECT Series.name, COUNT(Episode.id) AS episodes
FROM Series
    LEFT JOIN Episode ON Episode.seriesId = Series.id
GROUP BY Series.id, Series.name;
