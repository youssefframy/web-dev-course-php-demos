-- Unit 6, checkpoint 02, 6.2.2. Write your statements below.
-- Run these, then disconnect before going any further.
START TRANSACTION;
INSERT INTO Invoice(total, cashierId, clientId, issueTime) VALUES
    (100.00, 1, 3, NOW());
SET @ID = LAST_INSERT_ID();
