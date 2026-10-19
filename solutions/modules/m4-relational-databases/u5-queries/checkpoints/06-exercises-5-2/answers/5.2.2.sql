-- Unit 5, checkpoint 06, 5.2.2. Write one SELECT statement below.
SELECT Client.name, Client.surname, COUNT(Invoice.id) AS purchases
FROM Client
    LEFT JOIN Invoice ON Invoice.clientId = Client.id
GROUP BY Client.id, Client.name, Client.surname;
