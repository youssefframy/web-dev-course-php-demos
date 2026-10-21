-- Unit 6, checkpoint 03, 6.3.4. Write one SELECT statement below.
SELECT
    Item.name, Item.price,
    IF(Item.price >= 500, 'Premium', 'Standard') AS priceBand
FROM
    Item;
