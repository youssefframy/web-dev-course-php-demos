-- Unit 6, checkpoint 04, 6.4.1-balance. Write one SELECT statement below.
-- Brandon (client 6). The balance is NULL, because he has made no payments.
SELECT
    (SELECT SUM(Invoice.total) FROM Invoice WHERE Invoice.clientId = 6) -
    (SELECT SUM(Payment.total) FROM Payment WHERE Payment.clientId = 6) AS balance;
