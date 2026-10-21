-- Unit 6, checkpoint 02, 6.2.1. Write your statements below.
START TRANSACTION;
-- Step 1: Insert the invoice
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES
    (67.15, 1, 6, NOW());
-- Step 2: Get the ID of the invoice we've just inserted
SET @ID = LAST_INSERT_ID();
-- Step 3: Insert the invoice items
INSERT INTO InvoiceItem(invoiceId, unitPrice, qty, itemId) VALUES
    (@ID, 21.95, 2, 1),
    (@ID, 23.25, 1, 4);
COMMIT;
