# Exercise 2.3: Gallery, physical ERD

```mermaid
erDiagram
    ArtStyle ||--o{ Artist : "describes"
    Artist ||--o{ Artwork : "creates"
    ArtType ||--o{ Artwork : "classifies"
    Artwork ||--o{ ArtworkGroup : "is in"
    ArtGroup ||--o{ ArtworkGroup : "contains"
    Customer ||--o{ CustomerArtist : "likes"
    Artist ||--o{ CustomerArtist : "is liked by"
    Customer ||--o{ CustomerGroup : "likes"
    ArtGroup ||--o{ CustomerGroup : "is liked by"

    ArtStyle {
        INT id PK
        VARCHAR(50) name "unique"
    }
    Artist {
        INT id PK
        VARCHAR(50) name
        VARCHAR(50) surname "NULL"
        VARCHAR(100) birthPlace
        DATE dateOfBirth "NULL if unknown"
        INT artStyleId FK
    }
    ArtType {
        INT id PK
        VARCHAR(50) name "unique"
    }
    Artwork {
        INT id PK
        VARCHAR(150) title
        SMALLINT yearCreated "NULL if unknown"
        DECIMAL(10_2) price
        INT artTypeId FK
        INT artistId FK
    }
    ArtGroup {
        INT id PK
        VARCHAR(100) name "unique"
    }
    ArtworkGroup {
        INT id PK
        INT artworkId FK "unique with artGroupId"
        INT artGroupId FK
    }
    Customer {
        INT id PK
        VARCHAR(50) name
        VARCHAR(50) surname
        VARCHAR(100) addressLine1
        VARCHAR(100) addressLine2 "NULL"
        VARCHAR(50) city
        VARCHAR(50) country
        DECIMAL(10_2) totalSpent "DEFAULT 0.00"
    }
    CustomerArtist {
        INT id PK
        INT customerId FK "unique with artistId"
        INT artistId FK
    }
    CustomerGroup {
        INT id PK
        INT customerId FK "unique with artGroupId"
        INT artGroupId FK
    }
```

All columns are `NOT NULL` unless marked `NULL`. Every `id` is `AUTO_INCREMENT`. `DECIMAL(10_2)` stands for `DECIMAL(10,2)`, because Mermaid can't show the comma.
