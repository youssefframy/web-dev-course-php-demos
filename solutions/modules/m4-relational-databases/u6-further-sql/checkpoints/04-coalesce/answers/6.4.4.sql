-- Unit 6, checkpoint 04, 6.4.4. Write your statements below.
CREATE VIEW vwClientPayments AS
SELECT
    Client.id, Client.name, Client.surname,
    COALESCE((SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id), 0) AS totalPaid
FROM
    Client;

SELECT * FROM vwClientPayments;
