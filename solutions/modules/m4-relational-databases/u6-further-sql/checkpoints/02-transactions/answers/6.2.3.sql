-- Unit 6, checkpoint 02, 6.2.3. Write your statements below.
START TRANSACTION;
INSERT INTO Payment(issueTime, cashierId, clientId, paymentTypeId, total) VALUES
    (NOW(), 2, 4, 1, 200.99);
SET @ID = LAST_INSERT_ID();
INSERT INTO PaymentAllocation(paymentId, invoiceItemId, amount) VALUES
    (@ID, 2, 200.99);
COMMIT;
