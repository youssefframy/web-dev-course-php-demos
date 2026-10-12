# Exercise 2.1: Restaurant

Draw a logical ERD in [logical.md](logical.md) for this scenario.

## Scenario

- A restaurant has a number of items on its menu.
- For each menu item, we store its name, description, price and photo.
- Each menu item belongs to a category. For each category, we store its name, its description and the name of the employee in charge of it.
- We store the ingredients currently in stock in the kitchen: each ingredient's name, the amount in stock and the unit that amount is measured in (grams, litres and so on).
- We store which ingredients each dish on the menu needs, and how much of each.

## Requirement checklist

- [ ] Menu item, with name, description, price and photo.
- [ ] Category, with name, description and the employee in charge.
- [ ] Each menu item belongs to exactly one category.
- [ ] Ingredient, with name, amount in stock and unit of measurement.
- [ ] The link between menu items and ingredients records the amount of each ingredient a dish needs.
- [ ] Multiplicity is shown at both ends of every relationship.
- [ ] Any attribute or entity you added is listed as an assumption under the diagram.

Back to [checkpoint 04](../README.md).
