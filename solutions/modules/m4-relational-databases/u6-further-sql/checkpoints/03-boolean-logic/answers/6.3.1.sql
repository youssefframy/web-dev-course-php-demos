-- Unit 6, checkpoint 03, 6.3.1. Write one SELECT statement below.
SELECT
    Invoice.*,
    CASE
        WHEN Invoice.total >= 1000 THEN 'Large'
        WHEN Invoice.total >= 100 THEN 'Medium'
        ELSE 'Small'
    END AS invoiceSize
FROM
    Invoice;
