-- Unit 5, checkpoint 08, q3. Write one SELECT statement below.
SELECT Director.name, Director.surname
FROM Director
    LEFT JOIN Episode ON Episode.directorId = Director.id
WHERE Episode.id IS NULL;
