-- Unit 6, checkpoint 02, francesco. Write your statements below.
START TRANSACTION;
-- Step 1: Insert the invoice
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES
    (165.70, 2, 5, NOW());
-- Step 2: Get the ID of the invoice we've just inserted
SET @ID = LAST_INSERT_ID();
-- Step 3: Insert the invoice items
INSERT INTO InvoiceItem(invoiceId, unitPrice, qty, itemId) VALUES
    (@ID, 10.95, 6, 2),
    (@ID, 100.00, 1, 6);
COMMIT;
