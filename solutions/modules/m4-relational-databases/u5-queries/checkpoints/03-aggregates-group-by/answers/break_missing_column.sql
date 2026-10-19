-- Unit 5, checkpoint 03, breakit-group-by. This query should fail. It runs on a scratch copy.
SELECT Invoice.cashierId, Invoice.issueTime, SUM(Invoice.total)
FROM Invoice
GROUP BY Invoice.cashierId;
