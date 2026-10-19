-- Unit 5, checkpoint 07, 5.3.3. Write one SELECT statement below.
SELECT
    Cashier.name,
    Cashier.surname,
    CONCAT('€', SUM(Invoice.total)) AS sales
FROM Cashier
    JOIN Invoice ON Invoice.cashierId = Cashier.id
GROUP BY Cashier.id, Cashier.name, Cashier.surname
ORDER BY SUM(Invoice.total) DESC;
