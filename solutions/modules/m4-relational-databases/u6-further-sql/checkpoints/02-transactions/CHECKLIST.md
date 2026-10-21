
# Checkpoint 02: by hand

Tick each box when you have done it in `./sql shell pos`.

- [x] Without a transaction, the half-finished invoice for Francesco stayed after I disconnected, and I deleted it.
- [x] With `START TRANSACTION`, the half-finished invoice was gone when I reconnected.
- [x] After 6.2.1 to 6.2.3, `SELECT * FROM vwInvoiceDetails;` shows Francesco's and Brandon's invoices, and I did not change the view.

The view needs no change because it stores the query, not the rows. Each `SELECT` from it runs the query again on the current tables.
