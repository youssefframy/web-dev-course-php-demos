-- Unit 6, checkpoint 04, 6.4.5. Write your statements below.
CREATE VIEW vwClientStatement AS
SELECT
    T.*,
    T.spent - T.paid AS balance,
    CASE
        WHEN T.spent = 0 THEN 'No purchases'
        WHEN T.spent - T.paid = 0 THEN 'Settled'
        WHEN T.spent - T.paid <= 499 THEN 'Sales'
        ELSE 'Finance'
    END AS status
FROM
    (
        SELECT
            Client.id, Client.name, Client.surname,
            COALESCE((SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = Client.id), 0) AS spent,
            COALESCE((SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id), 0) AS paid
        FROM
            Client
    ) AS T;

SELECT * FROM vwClientStatement;
