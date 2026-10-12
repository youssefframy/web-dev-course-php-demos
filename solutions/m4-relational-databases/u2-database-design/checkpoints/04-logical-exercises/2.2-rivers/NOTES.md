# Notes: 2.2 Rivers

## Decisions

- RiverState is an entity in the logical design, because the relationship carries data: the river's length within that state.
- State has `capitalCity`. The scenario asks for it, but the deck's sample solution omits it.
- Mouth holds only `name` and `type` (sea or lake). The deck's sample adds `area` and `maxDepth`, which the scenario never asks for.
- Neighbouring states are a many-to-many relationship between State and itself. Logically it is one line from State back to State. Physically it is a linking table with two foreign keys to State.
- Neighbouring is symmetric. If Texas borders Oklahoma, then Oklahoma borders Texas. The solution stores each pair once, with `CHECK (stateId < neighbourStateId)`, so the reversed duplicate can't be inserted, and a state can't neighbour itself. Queries must look at both columns: `WHERE stateId = ? OR neighbourStateId = ?`.
- `totalLength` is stored, not worked out from RiverState. A river may continue beyond the states recorded, for example into Canada or Mexico.

## Acceptable alternatives

- Store both directions (A–B and B–A) without the CHECK. Queries become simpler, at the cost of keeping the pairs in step. Accept it if the student says so.
- Capital city as a City entity. It is over-modelled for this scenario, but fine if justified.
- Mouth type as a lookup table, or allowing "river" as a type (tributaries). This goes beyond the scenario but is realistic.
- A minimum of zero on the River side of RiverState.

## Watch for

- The length per state put on River or on State.
- A list of states in one column on River.
- A missing unique index on RiverState, which would allow the same river and state twice.
