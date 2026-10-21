
# Checkpoint 04: Using COALESCE

## Goal

Find the clients that `vwDebtors` loses, and fix the view and your earlier queries with `COALESCE()`.

## Concepts

- Any arithmetic with `NULL` gives `NULL`. `500 - NULL` is `NULL`, not 500.
- `SUM()` over no rows gives `NULL`, not 0.
- `COALESCE()` returns the first value in its list that is not `NULL`. `COALESCE(x, 0)` therefore turns a missing value into 0.

This is the bug from [Unit 5, checkpoint 06](../../../u5-queries/checkpoints/06-exercises-5-2/README.md#a-known-bug-kept-for-unit-6). `vwDebtors` is the same calculation in a view.

## Before you start

Your `pos` from checkpoint 03, with `vwDebtors` and `vwInvoiceDetails`. The checks add the checkpoint 02 rows and create both views from the slides first.

## Steps

1. `6.4.1.sql` (query): `SELECT * FROM vwDebtors;`. Which clients who owe money are missing? *Check: only Lorraine and Rachel are shown. Francesco and Brandon are missing.*
2. `6.4.1-balance.sql` (query): for one of the missing clients, run the view's two balance sub-queries on their own, subtracted, using that client's id. Explain the result in `ANSWERS.md`.
3. `6.4.2.sql` (script): fix `vwDebtors` with `COALESCE()`, as the slides do. `DROP VIEW vwDebtors;`, then `CREATE VIEW` again. *Check: 4 rows. Rachel now owes 500.00 and is assigned to Finance. The other three are assigned to Sales.*
4. `6.4.3.sql` (query): go back to 6.1.4 and fix it so that clients who have not bought anything show 0 instead of `NULL`. *Check: Lucy and Trevor show 0.00.*
5. `6.4.4.sql` (script): create `vwClientPayments`, showing every client's `id`, `name`, `surname` and `totalPaid`, with 0 for clients who have not paid anything. *Check: 6 rows. Lorraine has paid 1549.99 and Rachel 700.99.*
6. **Challenge.** `6.4.5.sql` (script): create `vwClientStatement`, showing for every client:
   - `id`, `name` and `surname`
   - `spent`: the total of their invoices, 0 if none
   - `paid`: the total of their payments, 0 if none
   - `balance`: `spent` minus `paid`
   - `status`: `No purchases` when `spent` is 0, `Settled` when the balance is 0, `Sales` when the balance is €499 or less, and `Finance` otherwise

   *Hint: calculate `spent` and `paid` in a sub-query in `FROM`, aliased as `T`, as `vwDebtors` does.* *Check: 6 rows. Lucy and Trevor are No purchases, Rachel is Finance, and the others are Sales.*
7. Run `./sql check m4u6-cp04`.

## Done when

- [ ] `./sql check m4u6-cp04` passes.
- [ ] You can say why Francesco's balance was `NULL`, and which of the two sub-queries needs the `COALESCE`.
- [ ] You can say why the fixed `vwDebtors` still leaves out Lucy and Trevor, and why `vwClientStatement` does not.

## Previous / Next

Previous: [03 Boolean logic](../03-boolean-logic/README.md). Next: back to [Module 4](../../../README.md).
