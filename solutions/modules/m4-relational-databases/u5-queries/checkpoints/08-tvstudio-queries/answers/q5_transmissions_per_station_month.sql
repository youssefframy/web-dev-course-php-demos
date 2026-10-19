-- Unit 5, checkpoint 08, q5. Write one SELECT statement below.
SELECT
    Station.name,
    DATE_FORMAT(Transmission.airDateTime, '%Y-%m') AS month,
    COUNT(*) AS transmissions
FROM Station
    JOIN Transmission ON Transmission.stationId = Station.id
GROUP BY Station.id, Station.name, DATE_FORMAT(Transmission.airDateTime, '%Y-%m')
ORDER BY Station.name, month;
