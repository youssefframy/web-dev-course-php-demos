# Exercise 2.2: Rivers

Draw a logical ERD in [logical.md](logical.md) for this scenario.

## Scenario

- The US Environmental Protection Agency needs a database of rivers.
- For each river, it stores the name, the total length, the mouth (the sea or lake the river empties into) and the states the river crosses.
- The length of the river within each state it crosses is recorded. A river that flows through three states has three length measurements, one for each state.
- For each state, the capital city is recorded, along with its neighbouring states.

## Requirement checklist

- [ ] River, with name and total length.
- [ ] Mouth, holding only what the scenario implies: its name, and whether it is a sea or a lake.
- [ ] Each river empties into exactly one mouth.
- [ ] State, with name and capital city.
- [ ] The link between rivers and states records the length of the river in that state.
- [ ] Neighbouring states are recorded. This is a relationship between State and itself.
- [ ] Multiplicity is shown at both ends of every relationship.
- [ ] Any attribute or entity you added is listed as an assumption under the diagram.

Back to [checkpoint 04](../README.md).
