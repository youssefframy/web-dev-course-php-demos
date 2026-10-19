-- Unit 5, checkpoint 02, 5.1.4. Write one SELECT statement below.
SELECT
    DATE(Payment.issueTime) AS paymentDate,
    SUM(Payment.total) AS received
FROM Payment
GROUP BY DATE(Payment.issueTime)
ORDER BY DATE(Payment.issueTime);
