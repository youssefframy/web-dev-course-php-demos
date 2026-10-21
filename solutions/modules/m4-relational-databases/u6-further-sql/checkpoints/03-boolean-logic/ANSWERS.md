
# Checkpoint 03: answers

Write your answer under the question, then tick the box.

- [x] 6.3.5: Which of `IF()` and `CASE` would you use if the query had to run on a DBMS other than MariaDB or MySQL, and why?

  Answer: `CASE`. It is part of the ISO SQL standard, so PostgreSQL, SQL Server, Oracle and SQLite all run it. `IF()` is a MariaDB and MySQL extension. SQL Server has `IIF()` instead, and PostgreSQL has neither.
