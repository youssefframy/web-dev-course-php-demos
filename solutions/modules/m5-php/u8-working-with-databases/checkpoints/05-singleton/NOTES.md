# Notes: Unit 8 checkpoints 01 to 05

- **Host.** The slides connect to `mariadb`. Here the host comes from `DB_HOST` (`db` in the dev container) and falls back to `127.0.0.1`, as `./sql` does.
- **Database name.** It is `shoppingcart`, in lower case, because `./sql reset shoppingcart` makes the database from the name in `course/chain.json`, and MariaDB database names are case-sensitive on Linux.
- **Seed.** `seeds/shoppingcart.sql` is written for the course. The slides link to a gist that is not vendored. It has the same four products. Prices are `DECIMAL(10,2)`.
- **Cleanup in checkpoint 04.** The slides delete `WHERE id >= 5`, which would remove real rows if a student had added their own. The answer inserts a row, updates it, then deletes only that row by id, so the page can be reloaded for ever.
- **`PARAM_BOOL`.** The slides give a bare `false` to `bindValue` in one place. The answer always gives the type. The checkpoint also teaches the `bindParam` error for a literal.
- **Persistent connections.** The "what does it do" step compares the server's global `Connections` counter. It is cumulative, so it shows the reuse. `Threads_connected` would show nothing useful, because a normal connection closes at the end of its request.
- **Singleton.** `DBConnect` in `05` is the plain version from the slides. The project version in checkpoint 06 adds `.env` and `__serialize`.
- **Fetch modes.** `FETCH_CLASS` uses public, untyped properties so every column fits. It works the same with a constructor-less class. `FETCH_FUNC` receives `DECIMAL` as a `string`, coerced by the `float` parameter type, since the answers do not use `strict_types`.
