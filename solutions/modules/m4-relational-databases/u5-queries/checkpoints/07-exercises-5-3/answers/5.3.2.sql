-- Unit 5, checkpoint 07, 5.3.2. Write one SELECT statement below.
SELECT
    SUM(InvoiceItem.unitPrice * InvoiceItem.qty) AS revenue,
    ROUND(SUM(InvoiceItem.unitPrice * InvoiceItem.qty) * 0.40, 2) AS profit
FROM InvoiceItem
    JOIN Item ON Item.id = InvoiceItem.itemId
    JOIN Invoice ON Invoice.id = InvoiceItem.invoiceId
    JOIN Category ON Category.id = Item.categoryId
WHERE Category.catCode = 'ENT'
    AND YEAR(Invoice.issueTime) = 2022;
