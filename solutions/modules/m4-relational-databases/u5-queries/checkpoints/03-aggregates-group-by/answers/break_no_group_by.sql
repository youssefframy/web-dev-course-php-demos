-- Unit 5, checkpoint 03, breakit-group. This query should fail. It runs on a scratch copy.
SELECT Invoice.cashierId, SUM(Invoice.total)
FROM Invoice;
