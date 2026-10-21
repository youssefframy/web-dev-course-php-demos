
# Checkpoint 04: answers

Write your answer under the question, then tick the box.

- [x] 6.4.1: Which clients who owe money are missing from `vwDebtors`, and why is their balance `NULL`?

  Answer: Francesco (165.70) and Brandon (67.15). Neither has made a payment, so `SUM(Payment.total)` adds up no rows and gives `NULL`. Their invoice total minus `NULL` is `NULL`, and `HAVING balance IS NOT NULL` then drops them. Wrapping the payments sub-query in `COALESCE(..., 0)` fixes it. The invoices sub-query is left alone on purpose: clients with no invoices keep a `NULL` balance, so `HAVING` still leaves out Lucy and Trevor, who owe nothing.
