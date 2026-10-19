-- Unit 5, checkpoint 06, 5.2.1. Write one SELECT statement below.
SELECT DISTINCT Client.id, Client.name, Client.surname
FROM Client
    JOIN Invoice ON Invoice.clientId = Client.id;
