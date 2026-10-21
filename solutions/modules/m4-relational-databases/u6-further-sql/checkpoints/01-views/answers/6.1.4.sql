-- Unit 6, checkpoint 01, 6.1.4. Write one SELECT statement below.
SELECT
    Client.*,
    (SELECT SUM(vwInvoiceDetails.total) FROM vwInvoiceDetails
     WHERE vwInvoiceDetails.clientId = Client.id) AS totalSpent
FROM
    Client;
