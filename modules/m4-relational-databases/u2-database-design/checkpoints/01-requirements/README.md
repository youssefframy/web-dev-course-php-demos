# Checkpoint 01: Requirements

## Goal

Read a scenario and pull out what the database must store: the entities, their attributes and the relationships between them.

## Concepts

Designing a database starts with words, not tables. Before drawing anything, you decide what needs to be stored.

- An entity is a thing you keep many records about, such as a book or a patron. In a relational database, an entity usually becomes a table.
- An attribute is one fact about an entity, such as a book's title. Attributes usually become columns.
- A relationship links two entities, for example "a patron borrows a book". In a relational database, a foreign key in one table points at the primary key of another.
- Multiplicity says how many of one entity can be linked to the other: one, zero or one, one or many, or zero or many.

At this stage you have not chosen a database type. You are describing the data, not how it will be stored.

The scenario text itself gives most of the answers:

| Look for | It usually becomes | Example |
|---|---|---|
| Nouns | Entities or attributes | "patron", "book", "due date" |
| Verbs | Relationships | "borrows", "belongs to" |
| Quantifiers | Multiplicity | "each", "at least one", "several", "may" |

### Worked example: a library

> A library lends books to its patrons. For each book, the library stores the ISBN, title, author and genre. For each patron, it stores their name and email address. A patron may borrow several books. Each loan is for exactly one book and one patron, and records the date borrowed, the due date and the date returned.

Nouns: library, book, ISBN, title, author, genre, patron, name, email address, loan, date borrowed, due date, date returned.

- Book, Patron and Loan are entities, because we keep many of each.
- ISBN, title, author and genre describe a book, so they are attributes of Book. The same reasoning applies to Patron and Loan.
- "Library" is neither. There is only one, and it is the system itself.

Verbs and quantifiers:

| Phrase | Relationship | Multiplicity |
|---|---|---|
| "Each loan is for exactly one book" | Loan to Book | Each loan has one and only one book. A book may have zero or many loans. |
| "Each loan is for exactly one ... patron" | Loan to Patron | Each loan has one and only one patron. |
| "A patron may borrow several books" | Patron to Loan | A patron has zero or many loans ("may" allows zero). |

Notice that "borrows" turned into its own entity, Loan, because the borrowing itself has facts to store (the dates). That is a common pattern, and you will see it again in checkpoint 03.

## Steps

1. Copy `templates/requirements-worksheet.md` (in the repository root) into this folder as `requirements.md`.
2. Fill it in for the ICEAdmin scenario:

   > ICE Malta wants a system to store employee details. Every employee works in at least one department, and some employees work in more than one. Each employee's salary must be stored, and past salaries must be kept as well as the current one.

3. The scenario does not list attributes. Choose sensible ones and record them as assumptions. At minimum: an employee has a name, surname and job title; a department has a name; a salary has an amount, a start date and an end date (empty for the current salary).
4. Commit your work.

## Done when

- [ ] `requirements.md` exists in this folder.
- [ ] It names three entities and lists attributes for each.
- [ ] Every relationship has a multiplicity on both sides, taken from the scenario's wording.
- [ ] Every attribute you invented is listed under assumptions.

## Previous / Next

Previous: [Unit overview](../../README.md). Next: [02 Logical ERD](../02-logical-erd/README.md).
