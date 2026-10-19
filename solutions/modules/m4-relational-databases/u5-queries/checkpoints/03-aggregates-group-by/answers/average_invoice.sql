-- Unit 5, checkpoint 03, scalar. Write one SELECT statement below.
SELECT CONCAT('€', ROUND(AVG(Invoice.total), 2)) AS averageInvoice
FROM Invoice;
