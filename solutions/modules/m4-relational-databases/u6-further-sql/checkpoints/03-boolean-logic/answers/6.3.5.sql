-- Unit 6, checkpoint 03, 6.3.5. Write one SELECT statement below.
SELECT
    Item.name, Item.price,
    CASE
        WHEN Item.price >= 500 THEN 'Premium'
        ELSE 'Standard'
    END AS priceBand
FROM
    Item;
