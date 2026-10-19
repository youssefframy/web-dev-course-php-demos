-- Unit 5, checkpoint 06, 5.2.3. Write one SELECT statement below.
SELECT
    (SELECT SUM(Invoice.total) FROM Invoice) -
    (SELECT SUM(Payment.total) FROM Payment) AS totalPending;
