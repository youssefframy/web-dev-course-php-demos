# Notes: 02 Logical ERD (library)

## Decisions

- Loan is an entity because the borrowing has its own data (three dates).
- Patron to Loan is zero or many, because "a patron may borrow". Each loan has exactly one patron.
- Book to Loan is zero or many. A new book has never been lent, and a book is lent many times over its life.

## Acceptable alternatives

- Patron }o--o{ Book drawn directly as many-to-many, with the dates noted on the relationship. This is weaker but defensible in a purely logical design. Ask the student where the dates would live.
- Author as its own entity. Accept it with a stated assumption (for example, books with several authors).
- Keys shown in the logical diagram. Not required, but not wrong.

## Watch for

- One and only one on the Patron side of Loan reversed, so that it reads "each patron has exactly one loan".
- `dateReturned` treated as required.
