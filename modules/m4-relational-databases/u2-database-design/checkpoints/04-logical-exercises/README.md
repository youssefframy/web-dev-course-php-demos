# Checkpoint 04: Logical design exercises

## Goal

Produce logical ERDs on your own for two new scenarios.

## Concepts

You now have the whole logical design process:

1. Pull entities, attributes and relationships out of the scenario (checkpoint 01).
2. Give each relationship a multiplicity at both ends, based on the scenario's wording (checkpoint 02).
3. Draw the result as a Mermaid `erDiagram` (checkpoint 02).

Two rules for these exercises:

- A plain many-to-many relationship can stay as it is in a logical design. You resolve it in the physical design.
- If the relationship itself carries data, it becomes an entity of its own, even in a logical design. "How much of each ingredient a dish needs" is a fact about the pairing of a dish and an ingredient, not about either one alone. This is the same pattern as Loan in the library example, where the borrowing had its own dates.

Store only what the scenario asks for. If you add an attribute the scenario does not mention, write it down as an assumption under your diagram.

## Steps

1. Do [2.1 Restaurant](2.1-restaurant/README.md).
2. Do [2.2 Rivers](2.2-rivers/README.md).
3. Commit, push and check that both diagrams render on GitHub.

## Done when

- [ ] Both `logical.md` files contain a complete diagram that renders on GitHub.
- [ ] Every item on each exercise's requirement checklist is ticked.

## Previous / Next

Previous: [03 Many-to-many relationships](../03-many-to-many/README.md). Next: [05 Physical ERD](../05-physical-erd/README.md).
