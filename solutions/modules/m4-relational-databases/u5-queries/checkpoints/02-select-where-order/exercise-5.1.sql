-- Exercise 5.1: all four answers, ready to run.
-- Run on the POS database:
--   ./sql reset pos
--   ./sql shell pos < solutions/modules/m4-relational-databases/u5-queries/checkpoints/02-select-where-order/exercise-5.1.sql
-- Or open it in SQLTools and run one statement at a time.
-- The expected results match the slide, and were checked on mariadb:11.4.13.


-- 1. Select all invoices having a total of €1,000 or greater.
--    (The slide says £. POS stores euros.)
-- Expected:
--   id | total   | cashierId | clientId | issueTime
--   1  | 1099.99 | 1         | 2        | 2022-01-22 10:12:13
--   2  | 1200.99 | 2         | 4        | 2022-01-22 10:30:12
SELECT *
FROM Invoice
WHERE Invoice.total >= 1000;


-- 2. Show sub-totals (unit price multiplied by quantity) for each invoice item,
--    with the invoice item id, and show the price as euros.
--    CONCAT turns the number into text, so use this for display only, not for sorting.
-- Expected:
--   id | subTotal
--   1  | €1099.99
--   2  | €1200.99
--   3  | €455.99
--   4  | €367.92
SELECT
    InvoiceItem.id,
    CONCAT('€', InvoiceItem.unitPrice * InvoiceItem.qty) AS subTotal
FROM InvoiceItem;


-- 3. Show a grand total of all sales as TotalSales.
-- Expected:
--   TotalSales
--   3124.89
SELECT SUM(Invoice.total) AS TotalSales
FROM Invoice;


-- 4. Show the total of payments received on each day, sorted by date.
--    The slide's answer groups by day but has no ORDER BY, so the order is not guaranteed.
--    ORDER BY makes "sorted by date" true.
-- Expected:
--   SaleDate   | Received
--   2022-01-22 | 2049.99
SELECT
    DATE(Payment.issueTime) AS SaleDate,
    SUM(Payment.total) AS Received
FROM Payment
GROUP BY DATE(Payment.issueTime)
ORDER BY DATE(Payment.issueTime);
