-- Unit 5, checkpoint 03, alias. Write one SELECT statement below.
SELECT
    Invoice.cashierId,
    SUM(Invoice.total) AS 'Total Sales'
FROM Invoice
GROUP BY Invoice.cashierId;
