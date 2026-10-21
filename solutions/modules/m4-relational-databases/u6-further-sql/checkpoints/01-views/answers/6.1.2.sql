-- Unit 6, checkpoint 01, 6.1.2. Write your statements below.
CREATE VIEW vwInvoiceDetails AS
SELECT
    Invoice.id AS invoiceId, Invoice.clientId, Client.name, Client.surname,
    Cashier.name AS cashierName, Invoice.total
FROM
    Invoice
    JOIN Client ON Client.id = Invoice.clientId
    JOIN Cashier ON Cashier.id = Invoice.cashierId;

SELECT * FROM vwInvoiceDetails;
