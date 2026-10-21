-- Unit 6, checkpoint 03, vwDebtors. Write your statements below.
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
            (SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = Client.id) AS balance
        FROM
            Client
        HAVING
            balance IS NOT NULL
    ) AS T;
