-- Unit 6, checkpoint 03, 6.3.2. Write one SELECT statement below.
SELECT
    Payment.id, Payment.total, PaymentType.typeName,
    CASE PaymentType.typeName
        WHEN 'Cash' THEN 'In store'
        WHEN 'Cheque' THEN 'In store'
        WHEN 'Credit' THEN 'Card'
        ELSE 'Online'
    END AS channel
FROM
    Payment
    JOIN PaymentType ON PaymentType.id = Payment.paymentTypeId;
