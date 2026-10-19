-- Unit 5, checkpoint 06, 5.2.4. Write one SELECT statement below.
SELECT Client.id, Client.name, Client.surname, T.balance
FROM Client
    JOIN (
        SELECT
            Client.id AS clientId,
            COALESCE((SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = Client.id), 0) -
            COALESCE((SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id), 0) AS balance
        FROM Client
    ) T ON T.clientId = Client.id
WHERE T.balance > 0;
