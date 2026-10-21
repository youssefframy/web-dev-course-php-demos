-- Unit 6, checkpoint 04, 6.4.2. Write your statements below.
DROP VIEW vwDebtors;

CREATE VIEW vwDebtors AS
SELECT
    T.*,
    CASE
        WHEN T.balance <= 499 THEN 'Sales'
        ELSE 'Finance'
    END AS Department
FROM
    (
        SELECT
            Client.id, Client.name, Client.surname,
            (SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = Client.id) -
            COALESCE((SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id), 0) AS balance
        FROM
            Client
        HAVING
            balance IS NOT NULL
    ) AS T;

SELECT * FROM vwDebtors;
