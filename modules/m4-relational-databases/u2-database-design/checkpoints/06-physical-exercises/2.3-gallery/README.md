# Exercise 2.3: Gallery

Build a physical ERD in ERD Editor for this scenario and export it to [schema.sql](schema.sql).

## Scenario

- A gallery keeps information about artists: their name, birthplace, age and style of art.
- For each piece of art, it stores the artist, title, year created, price and type (painting, sculpture and so on).
- Art is classified into groups of various kinds, such as "portraits", "still lifes" and "works of the 19th century". A piece of art can belong to several groups.
- The gallery also keeps information about its customers: each customer's name, address and total amount spent in the gallery, and the artists and groups of art that the customer likes.

## Requirement checklist

- [ ] Artist, with name, birthplace and style. Age is stored as a date of birth.
- [ ] Artwork, with title, year created, price and type, linked to exactly one artist.
- [ ] The year is stored in a type that can hold years before 1901.
- [ ] Group, linked to artworks through a linking table, since an artwork can be in several groups.
- [ ] Customer, with name, address and total spent.
- [ ] Price and total spent are `DECIMAL(10,2)`.
- [ ] Customer likes for artists, and customer likes for groups, each in its own linking table.
- [ ] Every linking table has a unique index on its two foreign keys.
- [ ] `scripts/validate.sh` passes.

Back to [checkpoint 06](../README.md).
