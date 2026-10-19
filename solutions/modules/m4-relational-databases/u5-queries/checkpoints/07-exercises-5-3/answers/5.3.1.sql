-- Unit 5, checkpoint 07, 5.3.1. Write one SELECT statement below.
SELECT
    Invoice.id,
    CONCAT('€', Invoice.total) AS total,
    CONCAT('€', ROUND(Invoice.total * 1.18, 2)) AS totalWithVAT
FROM Invoice;
