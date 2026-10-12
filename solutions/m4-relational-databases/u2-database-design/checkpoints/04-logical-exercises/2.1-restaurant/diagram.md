# Exercise 2.1: Restaurant, logical ERD

```mermaid
erDiagram
    Category ||--o{ MenuItem : "contains"
    MenuItem ||--|{ MenuItemIngredient : "needs"
    Ingredient ||--o{ MenuItemIngredient : "is used in"

    MenuItem {
        string name
        string description
        number price
        string photo
    }
    Category {
        string name
        string description
        string employeeInCharge
    }
    Ingredient {
        string name
        number amountInStock
        string unit
    }
    MenuItemIngredient {
        number amount "in the ingredient's unit"
    }
```

Assumptions:

- Every dish needs at least one ingredient.
- A category may be empty for a while, for example a new category before any items are added to it.
- The amount a dish needs is in the same unit as the ingredient's stock.
