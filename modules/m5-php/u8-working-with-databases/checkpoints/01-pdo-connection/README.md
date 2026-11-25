# Checkpoint 01: PDO connection

## Goal

Connect a PHP page to the `shoppingcart` database with PDO, handle a failed connection, and turn on a persistent connection.

## Concepts

- PDO (PHP Data Objects) is one interface for many databases. Your code calls the same methods whether the database is MariaDB, PostgreSQL or SQLite. Only a driver and the connection string change. The driver for MariaDB and MySQL is `pdo_mysql`.
- A DSN (Data Source Name) is the connection string. It is the driver name, a colon, then `key=value` pairs separated by semicolons: `mysql:host=db;dbname=shoppingcart;charset=utf8mb4`. `host` is the server, `dbname` is the database, `charset` is the character set, and `port` is only needed if the server does not use 3306.
- `new PDO($dsn, $user, $password, $options)` connects. If it cannot, it throws a `PDOException`, and an uncaught exception shows the file, the line and the arguments. Wrap the connection in `try`/`catch` so the visitor sees a short message instead.
- Never show `$e->getMessage()` to visitors of a real site, because it can reveal server details. Here it helps you learn.
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` makes every later PDO error an exception too. PHP 8 already does this by default, and saying it out loud makes the code read the same on older versions.
- `PDO::ATTR_PERSISTENT => true` keeps the connection open after the script ends, and the next request with the same credentials reuses it. That saves the cost of connecting each time.
- Your username and password are in the code here, which is fine for this practice database. Checkpoint 06 moves them to a file that is not committed.

## Before you start

Load the database (see the [unit README](../../README.md)) and start the server in the unit's [`pdo/`](../../pdo) folder. Create `index.php` there.

## Steps

1. Follow along: connect, and show the server version. Start `index.php` with:

   ```php
   <?php
   // In the dev container DB_HOST is "db". On your own machine the server is on 127.0.0.1.
   $host = getenv('DB_HOST') ?: '127.0.0.1';
   $dsn = "mysql:host=$host;dbname=shoppingcart;charset=utf8mb4";

   $dbh = null;
   $error = null;

   try {
       $dbh = new PDO($dsn, 'root', 'root', [
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_PERSISTENT => true,
       ]);
   } catch (PDOException $e) {
       $error = $e->getMessage();
   }
   ?>
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <title>PDO connection</title>
   </head>

   <body>
       <h1>PDO connection</h1>
       <?php if ($dbh !== null) { ?>
           <p>Connected to MariaDB <?= htmlspecialchars($dbh->getAttribute(PDO::ATTR_SERVER_VERSION)) ?>.</p>
       <?php } else { ?>
           <p>Could not connect: <?= htmlspecialchars($error) ?></p>
       <?php } ?>
   </body>

   </html>
   ```

   *Check: `Connected to MariaDB 11.4.…`.*

2. Follow along: break it on purpose. Change the password from `'root'` to `'wrong'` and reload.

   *Check: `Could not connect: SQLSTATE[HY000] [1045] Access denied for user 'root'@…`.* Put the password back.

3. Follow along: break it a different way. Change `dbname=shoppingcart` to `dbname=nothing`.

   *Check: `[1049] Unknown database 'nothing'`.* Put it back.

4. Follow along: remove the `try`/`catch` and break the password again, just to see an uncaught exception. Put the `try`/`catch` back.

5. Your turn: change `$host` to `'no-such-host'`. *Check: a message about not being able to resolve the host.* Put it back.

6. Your turn: see a persistent connection at work. The server counts every connection it has ever accepted. Open a database shell in a terminal:

   ```sh
   ./sql shell shoppingcart
   ```

   At the prompt, run `SHOW GLOBAL STATUS LIKE 'Connections';` and note the number. Reload the page 10 times, then run it again.

   *Check: with `ATTR_PERSISTENT` set to `true`, the number goes up by at most 1 (the first request opens the connection, and the rest reuse it). Set it to `false`, reload 10 times, and it goes up by about 11 (your shell, plus one per reload).* Put it back to `true` after.

## Done when

- [ ] The page shows the MariaDB version.
- [ ] A wrong password and a wrong database name each show a short message, not a stack trace.
- [ ] You can name the three parts of a DSN you used.
- [ ] You can say what a persistent connection saves.

## Previous / Next

Previous: [Unit 7 Object-Oriented Programming](../../../u7-object-oriented-programming/README.md). Next: [02 Queries and fetch modes](../02-queries-fetch-modes/README.md).
