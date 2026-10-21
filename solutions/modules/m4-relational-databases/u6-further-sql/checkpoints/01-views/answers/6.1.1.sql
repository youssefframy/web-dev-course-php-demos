-- Unit 6, checkpoint 01, 6.1.1. Write your statements below.
CREATE VIEW vwHighValueInvoices AS
SELECT
    Invoice.*
FROM
    Invoice
WHERE
    Invoice.total >= 1000;

SELECT * FROM vwHighValueInvoices;
