-- Unit 5, checkpoint 06, 5.2.5. Write one SELECT statement below.
SELECT Category.catName, SUM(InvoiceItem.unitPrice * InvoiceItem.qty) AS sales
FROM InvoiceItem
    JOIN Item ON Item.id = InvoiceItem.itemId
    JOIN Category ON Category.id = Item.categoryId
GROUP BY Category.id, Category.catName;
