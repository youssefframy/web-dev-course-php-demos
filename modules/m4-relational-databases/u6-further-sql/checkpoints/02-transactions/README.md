
# Checkpoint 02: Transactions

## Goal

Group statements into a transaction, so that either all of them are kept or none are. Then see what happens when a connection drops halfway.

## Concepts

- A single SQL statement is atomic: it either succeeds completely or fails completely.
- A transaction extends this to a group of statements. `START TRANSACTION` begins the group and `COMMIT` saves it.
- If the connection ends before `COMMIT`, the server rolls the changes back.
- `SET @ID = LAST_INSERT_ID();` stores the id of the row you just inserted, so the next statement can use it.

A rolled-back `INSERT` still uses up its auto-increment id. That is why the workbook's invoices jump from 3 to 6: id 4 was deleted, and id 5 was rolled back.

## Before you start

Your `pos` from checkpoint 01, with `vwInvoiceDetails`.

## Follow along: disconnecting halfway

The checks can't disconnect for you, so do this part by hand in `./sql shell pos`. Type `exit` to disconnect, then run `./sql shell pos` again to reconnect. Francesco (client 5) buys 6 Matei X50 spoons (item 2, 10.95 each) and a Russell Hobbs toaster (item 6, 100.00) from Roger (cashier 2). The invoice total is 165.70.

1. Without a transaction: insert the invoice (step 1) and store its id (step 2). Disconnect.
2. Reconnect and look at `Invoice` and `InvoiceItem`. The invoice is there, with no items. Delete it.
3. Do it again, starting with `START TRANSACTION;`. Disconnect after step 2.
4. Reconnect. The invoice is not there.
5. `answers/francesco.sql` (script): write the whole transaction, through to `COMMIT`. Run it with `./sql shell pos < answers/francesco.sql`.

*Check: Francesco now has one invoice of 165.70, with two invoice items. Your invoice id may differ from the slides.*

## Steps

1. `6.2.1.sql` (script): Brandon Pace (client 6) buys the following from Kristina Grech (cashier 1). The invoice total is 67.15. Record the invoice and its items in one transaction, using `NOW()` for the issue time.

   | Item | Item id | Unit price | Qty |
   |---|---|---|---|
   | Matei Knife 21 Series | 1 | 21.95 | 2 |
   | Novelty Inc. Maxi Mug 5L | 4 | 23.25 | 1 |

2. `6.2.2.sql` (script): Trevor Musu (client 3) wants a Russell Hobbs toaster (item 6, 100.00), served by Kristina. Start a transaction, insert the invoice and store its id. Stop there: no item, no `COMMIT`. Run the file in the shell, then check `Invoice`. *Check: there is no invoice for Trevor.* The check makes sure the file has no `COMMIT` or `ROLLBACK`. It also checks that the invoice was really inserted and then rolled back, because the rolled-back id is used up.
3. `6.2.3.sql` (script): Rachel Bonavia (client 4) pays another 200.99 in cash (payment type 1) to Roger Vassallo (cashier 2). The payment goes against her fridge, which is invoice item 2. In one transaction, insert the payment, store its id, and insert a row into `PaymentAllocation` linking the payment to invoice item 2 for 200.99. *Check: Payment has 4 rows and PaymentAllocation has 5.*
4. Run each file in your own `pos` with `./sql shell pos < answers/...`, then `./sql check m4u6-cp02`.
5. Run `SELECT * FROM vwInvoiceDetails;` again without changing the view. Tick the box in `CHECKLIST.md`.

## Done when

- [ ] `./sql check m4u6-cp02` passes.
- [ ] Your own `pos` has Francesco's and Brandon's invoices and Rachel's second payment, and no invoice for Trevor.
- [ ] You can say what happens to an uncommitted transaction when the connection ends.

## Previous / Next

Previous: [01 Views](../01-views/README.md). Next: [03 Boolean logic](../03-boolean-logic/README.md).
