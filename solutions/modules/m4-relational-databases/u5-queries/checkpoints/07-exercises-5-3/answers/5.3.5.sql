-- Unit 5, checkpoint 07, 5.3.5. Write one SELECT statement below.
SELECT Invoice.*
FROM Invoice
WHERE EXISTS (
        SELECT 1
        FROM InvoiceItem
            JOIN Item ON Item.id = InvoiceItem.itemId
            JOIN Category ON Category.id = Item.categoryId
        WHERE InvoiceItem.invoiceId = Invoice.id AND Category.catCode = 'ENT'
    )
    AND EXISTS (
        SELECT 1
        FROM InvoiceItem
            JOIN Item ON Item.id = InvoiceItem.itemId
            JOIN Category ON Category.id = Item.categoryId
        WHERE InvoiceItem.invoiceId = Invoice.id AND Category.catCode = 'CRK'
    );
