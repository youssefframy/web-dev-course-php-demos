-- Unit 6, checkpoint 03, 6.3.6. Write your statements below.
CREATE VIEW vwDeliveryPlan AS
SELECT
    InvoiceItem.id, InvoiceItem.unitPrice, Item.name, Category.catCode,
    CASE
        WHEN Category.catCode = 'ENT' AND InvoiceItem.unitPrice >= 1000 THEN 'Van'
        WHEN Category.catCode = 'ENT' THEN 'Large Parcel by Courier'
        WHEN Category.catCode = 'CUT' OR Category.catCode = 'CRK' THEN 'Fragile Parcel'
        WHEN Category.catCode = 'APP' THEN 'Van'
        ELSE 'Delivery not available'
    END AS deliveryMode
FROM
    InvoiceItem
    JOIN Item ON Item.id = InvoiceItem.itemId
    JOIN Category ON Category.id = Item.categoryId;

SELECT * FROM vwDeliveryPlan WHERE vwDeliveryPlan.deliveryMode = 'Van';
