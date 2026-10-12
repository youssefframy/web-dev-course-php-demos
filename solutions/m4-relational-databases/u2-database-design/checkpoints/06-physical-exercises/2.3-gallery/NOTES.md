# Notes: 2.3 Gallery

## Decisions

- Age is stored as `dateOfBirth`, because age changes every year and can be worked out when needed. It is nullable, since birth dates of historical artists are often unknown.
- `yearCreated` is `SMALLINT`, not `YEAR`. MariaDB's `YEAR` only covers 1901 to 2155, and the scenario mentions 19th-century work. Like the date of birth, it is nullable.
- `price` and `totalSpent` are `DECIMAL(10,2)`. The deck's sample uses `FLOAT`, which is wrong for money.
- Style and type are lookup tables (ArtStyle and ArtType). Both lists grow, and each value is shared by many rows.
- The group table is called ArtGroup, because `GROUP` is a reserved word.
- Artist and Customer both have `name` and `surname`. The deck's sample gives Artist only a name. An artist's surname is nullable, for artists known by one name.
- Three linking tables (ArtworkGroup, CustomerArtist and CustomerGroup), each with a unique index on its pair.
- `totalSpent` is stored. The scenario records no purchases, so it can't be derived. If a Purchase entity is added later, the total should be calculated from it instead.

## Acceptable alternatives

- `INT` for the year. It works, but it is wider than needed.
- Style or type as `ENUM`. It is weaker, because the lists will grow, but accept it if the student acknowledges that.
- A single address column, or an Address entity.
- An artist with several styles (an ArtistStyle linking table). This goes beyond the scenario but is defensible.
- An artwork's artist as optional (unknown artist). Accept it with a note.

## Watch for

- `FLOAT` for money.
- `YEAR` for `yearCreated`.
- Storing `age`.
- Customer likes stored as a list in one column.
- Linking tables without a unique pair.
