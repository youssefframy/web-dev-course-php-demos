-- Unit 5, checkpoint 02, 5.1.2. Write one SELECT statement below.
SELECT
    InvoiceItem.id,
    CONCAT('€', InvoiceItem.unitPrice * InvoiceItem.qty) AS subTotal
FROM InvoiceItem;
