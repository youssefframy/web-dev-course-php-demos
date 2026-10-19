-- Unit 5, checkpoint 08, q4. Write one SELECT statement below.
SELECT Series.name
FROM Series
WHERE NOT EXISTS (
    SELECT 1
    FROM Episode
        JOIN Transmission ON Transmission.episodeId = Episode.id
    WHERE Episode.seriesId = Series.id
);
