-- Unit 6, checkpoint 01, 6.1.3. Write your statements below.
CREATE VIEW vwItemCategory AS
SELECT
    Item.id, Item.name, Category.catName
FROM
    Item
    JOIN Category ON Category.id = Item.categoryId;
