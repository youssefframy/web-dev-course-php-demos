-- Unit 6, checkpoint 01, 6.1.3-join. Write one SELECT statement below.
SELECT
    InvoiceItem.id, InvoiceItem.qty, vwItemCategory.name, vwItemCategory.catName
FROM
    InvoiceItem
    JOIN vwItemCategory ON vwItemCategory.id = InvoiceItem.itemId;
