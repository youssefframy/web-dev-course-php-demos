-- Unit 5, checkpoint 06, 5.2.6. Write one SELECT statement below.
SELECT Item.name, PaymentAllocation.amount
FROM Item
    JOIN InvoiceItem ON InvoiceItem.itemId = Item.id
    JOIN PaymentAllocation ON PaymentAllocation.invoiceItemId = InvoiceItem.id
    JOIN Payment ON Payment.id = PaymentAllocation.paymentId
    JOIN PaymentType ON PaymentType.id = Payment.paymentTypeId
WHERE PaymentType.typeName = 'Credit'
    AND Payment.issueTime >= '2022-01-22 00:00:00'
    AND Payment.issueTime < '2022-01-22 12:00:00';
