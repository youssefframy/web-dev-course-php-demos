
-- The rows checkpoint 02 adds to pos: Francesco's and Brandon's invoices, and Rachel's second payment.
-- Load it into a freshly reset pos:  ./sql shell pos < modules/m4-relational-databases/u6-further-sql/part2-data.sql
-- The ids match the workbook, where the invoices that were rolled back or deleted used up ids 4 and 5.
INSERT INTO Invoice (id, total, cashierId, clientId, issueTime) VALUES (6, 165.70, 2, 5, '2022-01-24 10:05:00'), (7, 67.15, 1, 6, '2022-01-24 11:20:00');
INSERT INTO InvoiceItem (id, invoiceId, unitPrice, qty, itemId) VALUES (5, 6, 10.95, 6, 2), (6, 6, 100.00, 1, 6), (7, 7, 21.95, 2, 1), (8, 7, 23.25, 1, 4);
INSERT INTO Payment (id, issueTime, cashierId, clientId, paymentTypeId, total) VALUES (4, '2022-01-24 11:45:00', 2, 4, 1, 200.99);
INSERT INTO PaymentAllocation (id, paymentId, invoiceItemId, amount) VALUES (5, 4, 2, 200.99);
