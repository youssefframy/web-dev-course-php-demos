-- Unit 5, checkpoint 07, 5.3.4. Write one SELECT statement below.
SELECT
    Client.name,
    Client.surname,
    Category.catName,
    CONCAT('€', SUM(InvoiceItem.unitPrice * InvoiceItem.qty)) AS spend
FROM Client
    JOIN Invoice ON Invoice.clientId = Client.id
    JOIN InvoiceItem ON InvoiceItem.invoiceId = Invoice.id
    JOIN Item ON Item.id = InvoiceItem.itemId
    JOIN Category ON Category.id = Item.categoryId
GROUP BY Client.id, Client.name, Client.surname, Category.id, Category.catName;
