-- Unit 5, checkpoint 01, warmup-3. Write one SELECT statement below.
SELECT Invoice.id, Invoice.total, Invoice.issueTime
FROM Invoice
WHERE Invoice.cashierId = 2;
