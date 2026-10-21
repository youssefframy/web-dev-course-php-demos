-- Unit 6, checkpoint 03, 6.3.3. Write one SELECT statement below.
SELECT
    InvoiceItem.id, Item.name, InvoiceItem.qty, InvoiceItem.unitPrice,
    CASE
        WHEN InvoiceItem.qty >= 5 AND InvoiceItem.unitPrice <= 50 THEN 'Bulk discount'
        WHEN InvoiceItem.unitPrice >= 1000 OR InvoiceItem.qty >= 10 THEN 'Manager approval'
        ELSE 'Standard'
    END AS handling
FROM
    InvoiceItem
    JOIN Item ON Item.id = InvoiceItem.itemId;
