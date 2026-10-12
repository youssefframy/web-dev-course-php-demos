# Checkpoint 02: Logical ERD

## Goal

Turn a set of requirements into a logical Entity Relationship Diagram (ERD), using crow's foot notation written in Mermaid.

## Concepts

An ERD shows entities, their attributes, the relationships between them and the multiplicity of each relationship. Technical and non-technical people can both read it, which makes it a good thing to agree on before any SQL is written.

A logical ERD describes the data only. It does not commit to a type of database, so it can:

- leave out primary and foreign keys
- keep many-to-many relationships as they are

There are several ERD notations. This course uses crow's foot, which is widely used in industry. Each end of a relationship line carries a symbol. The symbol nearest an entity says how many of that entity can be linked to one record at the other end.

| Symbol | Meaning | Mermaid (left end / right end) |
|---|---|---|
| Single bar | One | `\|\|` / `\|\|` |
| Crow's foot | Many | `}\|` / `\|{` |
| Double bar | One and only one (mandatory) | `\|\|` / `\|\|` |
| Bar and crow's foot | One or many | `}\|` / `\|{` |
| Circle and bar | Zero or one (optional) | `\|o` / `o\|` |
| Circle and crow's foot | Zero or many (optional) | `}o` / `o{` |

Mermaid always records a minimum (zero or one) as well as a maximum, so plain "one" and "many" are written the same way as "one and only one" and "one or many". Decide the minimum from the scenario's wording: "may" usually means zero, and "each" or "at least one" usually means one.

In Mermaid, `--` draws a solid line and `..` draws a dashed one. ERD Editor and many textbooks use a dashed line when the foreign key is not part of the child's primary key. Our tables all have their own `id`, so you can use `--` throughout.

### Why Mermaid

GitHub renders Mermaid diagrams directly in Markdown files, so your diagram is visible in your repository and in pull requests with no extra software. Use Mermaid for every logical design in this course. In VS Code, open the Markdown preview (Ctrl+Shift+V, or Cmd+Shift+V on a Mac) to see it as you type.

A relationship line in Mermaid looks like this:

```
Patron ||--o{ Loan : "makes"
```

Read it left to right as "one patron makes zero or many loans", and right to left as "each loan is made by exactly one patron".

### Worked example: ICEAdmin

See [iceadmin-logical.md](iceadmin-logical.md). It is the ICEAdmin scenario from checkpoint 01, drawn as a logical ERD.

## Steps

1. Read `iceadmin-logical.md` and compare it with your worksheet from checkpoint 01. Note any differences in multiplicity and decide which reading of the scenario is right.
2. Create `library-logical.md` in this folder. Draw the library example from checkpoint 01 (Book, Patron, Loan) as a Mermaid `erDiagram`, with attributes and both relationships.
3. Preview it and check that each line reads correctly in both directions.
4. Commit and push, then open the file on GitHub to confirm the diagram renders.

If you are starting here, the library scenario is: a library lends books to patrons; it stores each book's ISBN, title, author and genre, and each patron's name and email address; a patron may borrow several books; each loan is for one book and one patron, and records the date borrowed, the due date and the date returned.

## Done when

- [ ] `library-logical.md` contains a Mermaid `erDiagram` with Book, Patron and Loan.
- [ ] Each entity lists its attributes.
- [ ] Both relationships have a multiplicity at each end that matches the scenario.
- [ ] The diagram renders on GitHub.

## Previous / Next

Previous: [01 Requirements](../01-requirements/README.md). Next: [03 Many-to-many relationships](../03-many-to-many/README.md).
