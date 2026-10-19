-- Extra POS data for demonstrations in class. Load it on top of seeds/pos.sql:
--   ./sql reset pos
--   ./sql shell pos < seeds/pos-demo.sql
--
-- The checks never use this file: they always start from seeds/pos.sql, so results match the slides.
-- With this data loaded, your results will differ from the slides. Run ./sql reset pos to go back.
-- It is safe to run twice: rows that already exist are skipped (INSERT IGNORE on fixed ids).
--
-- What it adds:
--   invoice 4  2022-01-23  Lucy Pace          87.70  multi-quantity cutlery, paid in cash
--   invoice 5  2022-01-23  Trevor Musu      1199.99  paid in two instalments, the second in February
--   invoice 6  2022-01-24  Francesco Rossi   981.73  just under 1000, paid by card
--   invoice 7  2022-01-25  Brandon Pace     1476.93  not paid at all
--   invoice 8  2022-02-01  Lorraine Pulis   1000.00  exactly 1000, paid by cheque

INSERT IGNORE INTO Invoice (id, total, cashierId, clientId, issueTime) VALUES
    (4,   87.70, 1, 1, '2022-01-23 09:15:40'),
    (5, 1199.99, 2, 3, '2022-01-23 11:02:05'),
    (6,  981.73, 1, 5, '2022-01-24 16:45:00'),
    (7, 1476.93, 2, 6, '2022-01-25 10:20:30'),
    (8, 1000.00, 1, 2, '2022-02-01 14:05:00');

-- Each invoice total is the sum of its items' unitPrice * qty.
INSERT IGNORE INTO InvoiceItem (id, invoiceId, unitPrice, qty, itemId) VALUES
    ( 5, 4,   21.95,  2, 1),   -- Matei Knife 21 Series     43.90
    ( 6, 4,   10.95,  4, 2),   -- Matei X50 Spoon           43.80
    ( 7, 5, 1099.99,  1, 5),   -- Sony Bravia X3456       1099.99
    ( 8, 5,  100.00,  1, 6),   -- Russell Hobbs Toaster    100.00
    ( 9, 6,  455.99,  2, 8),   -- Microsoft Hololens 2     911.98
    (10, 6,   23.25,  3, 4),   -- Novelty Maxi Mug          69.75
    (11, 7, 1200.99,  1, 7),   -- American Fridge B1      1200.99
    (12, 7,   45.99,  6, 3),   -- Helfing Plate            275.94
    (13, 8,  100.00, 10, 6);   -- Russell Hobbs Toaster   1000.00

-- Payment types: 1 Cash, 2 Cheque, 3 Credit, 4 Online.
INSERT IGNORE INTO Payment (id, issueTime, cashierId, clientId, paymentTypeId, total) VALUES
    (4, '2022-01-23 09:16:10', 1, 1, 1,   87.70),
    (5, '2022-01-23 11:05:00', 2, 3, 3,  600.00),
    (6, '2022-01-24 16:47:12', 1, 5, 3,  981.73),
    (7, '2022-02-01 10:00:00', 2, 3, 4,  599.99),
    (8, '2022-02-01 14:06:30', 1, 2, 2, 1000.00);

-- How each payment is split across invoice items. Each payment's allocations add up to its total.
INSERT IGNORE INTO PaymentAllocation (id, paymentId, invoiceItemId, amount) VALUES
    ( 5, 4,  5,   43.90),
    ( 6, 4,  6,   43.80),
    ( 7, 5,  7,  600.00),
    ( 8, 6,  9,  911.98),
    ( 9, 6, 10,   69.75),
    (10, 7,  7,  499.99),
    (11, 7,  8,  100.00),
    (12, 8, 13, 1000.00);
