# Unit 8: Working with Databases

Until now your PHP pages kept their data in arrays and the session, and lost it when the session ended. In this unit you connect PHP to MariaDB with PDO. You run queries, fetch the results in different shapes, and use prepared statements to send values safely. You then add, change and delete rows, and share one connection through the Singleton pattern. Then you use all of it to give the shopping cart from Unit 7 a database, user accounts and a login.

## What you need

The same dev container as Units 1 to 7, and the database running. PDO and its MySQL driver come with the container. Check them in a terminal:

```sh
php -m | grep pdo_mysql
```

It should print `pdo_mysql`. Then load the shopping cart database. Run this in the repository root:

```sh
./sql reset shoppingcart
```

*Check: it prints `shoppingcart now matches the reference state after m5u8.` Open phpMyAdmin at <http://localhost:8080> and you see a `shoppingcart` database with four tables.*

If you load the database again later, all rows you added (users, carts) are gone, and the four products are back.

## How to run your pages

Checkpoints 01 to 05 grow pages in the unit's [`pdo/`](pdo) folder. Start the server there:

```sh
cd modules/m5-php/u8-working-with-databases/pdo
php -S localhost:8000
```

Checkpoints 06 to 08 work on the app in [`shoppingcart/`](shoppingcart), which is the finished cart from Unit 7. Start the server in that folder instead.

The database host is `db` in the dev container, and `127.0.0.1` on your own machine. The pages read it from the `DB_HOST` environment variable, which the dev container sets, and fall back to `127.0.0.1`. The slides say `mariadb`, which is the name used in the course's other setup and does not exist here.

Checkpoints 01 to 04 each write their own `index.php` in `pdo/`. Keep your old one first by renaming it, for example to `index-01.php`. Checkpoint 05 uses four files. Each of 07 and 08 starts from the end of the one before. If you skipped one, its README gives a `cp` command that copies the reference answer into place.

Keep formatting every PHP file before you save it (Shift+Alt+F, or Shift+Option+F on a Mac).

## Checkpoints

| No. | Title | Outcome |
|---|---|---|
| 01 | [PDO connection](checkpoints/01-pdo-connection/README.md) | Connect with a DSN, catch errors, and see what persistent connections do |
| 02 | [Queries and fetch modes](checkpoints/02-queries-fetch-modes/README.md) | `query()` and the ways to fetch the result: arrays, objects, a column, a class, a function |
| 03 | [Prepared statements](checkpoints/03-prepared-statements/README.md) | Named and `?` parameters, `bindValue`, `bindParam`, `bindColumn`, and why they stop SQL injection |
| 04 | [Insert, update, delete](checkpoints/04-insert-update-delete/README.md) | Changing data, `rowCount()` and `lastInsertId()` |
| 05 | [The Singleton pattern](checkpoints/05-singleton/README.md) | One shared connection per page, in a `DBConnect` class |
| 06 | [Cart: database and model](checkpoints/06-cart-database-and-model/README.md) | Credentials in `.env`, and `DBConnect`, `User`, `Product` and `Cart` that read and write the database |
| 07 | [Cart: accounts, views and checkout](checkpoints/07-accounts-views-checkout/README.md) | Register, sign in, a saved cart, an account page and a checkout that records the order |
| 08 | [Self-study](checkpoints/08-self-study/README.md) | An admin page that adds, edits and deletes products, and the extra credit: forgot password |

Checkpoints 01 to 05 are separate demos that use the same database. Checkpoints 06 to 08 build on each other.

Reference answers are in [`solutions/`](../../../solutions/modules/m5-php/u8-working-with-databases).
