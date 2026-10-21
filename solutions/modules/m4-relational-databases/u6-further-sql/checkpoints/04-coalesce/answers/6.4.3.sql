-- Unit 6, checkpoint 04, 6.4.3. Write one SELECT statement below.
SELECT
    Client.*,
    COALESCE((SELECT SUM(vwInvoiceDetails.total) FROM vwInvoiceDetails
              WHERE vwInvoiceDetails.clientId = Client.id), 0) AS totalSpent
FROM
    Client;
