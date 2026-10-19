# Notes: 2.1 Restaurant

## Decisions

- Four entities: MenuItem, Category, Ingredient, and MenuItemIngredient.
- MenuItemIngredient is an entity even in the logical design, because the relationship carries data: how much of the ingredient the dish needs. A bare many-to-many line has nowhere to put that amount.
- Category has `description`. The scenario asks for it, but the deck's sample solution omits it.
- `employeeInCharge` is a plain attribute. The scenario asks only for the employee's name.
- `price` is `DECIMAL(10,2)`, never `FLOAT`.
- Amounts are `DECIMAL(10,3)`, so 0.125 litres stays exact.
- `photo` is stored as a URL or path (`photoUrl`), not as image data. That is the usual practice. The photo is optional so that an item can be listed before it is photographed.
- Each linking pair is unique: a dish lists each ingredient once.

## Acceptable alternatives

- Employee as its own entity, linked to Category. This is the deck's sample. It is fine if the student justifies it (for example, the same employee might be in charge of several categories), but it goes beyond the scenario.
- `unit` as an `ENUM('g','kg','ml','l','units')` or as a Unit lookup table. Both are acceptable. A lookup table is better if units will grow.
- A logical diagram with MenuItem }o--o{ Ingredient and the amount written in the relationship label. Accept it if the student says the amount belongs to the relationship.
- A minimum of zero on the MenuItem side of MenuItemIngredient (dishes not yet costed).

## Watch for

- `price FLOAT`.
- The amount stored on Ingredient instead of on the link, which loses which dish it belongs to.
- No unique pair on the linking table.
