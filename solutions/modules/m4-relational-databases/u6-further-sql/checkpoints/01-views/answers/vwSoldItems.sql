-- Unit 6, checkpoint 01, vwSoldItems. Write your statements below.
CREATE VIEW vwSoldItems AS
SELECT
    Item.name, InvoiceItem.unitPrice, InvoiceItem.qty
FROM
    InvoiceItem
    JOIN Item ON Item.id = InvoiceItem.itemId;

SELECT * FROM vwSoldItems;
