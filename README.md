# ICE Malta: Back-End Development With PHP

Exercises for the Back-End Development With PHP course. Each unit is split into short checkpoints. Work through them in order. If you join part-way through, each checkpoint tells you what you need in order to start there.

## Modules

| Module | Unit | Checkpoints |
|---|---|---|
| [M4 Relational Databases](modules/m4-relational-databases/README.md) | [U2 Database Analysis & Design](modules/m4-relational-databases/u2-database-design/README.md) | 01 to 07 |

More units will be added here as the course progresses.

## What you need

- Visual Studio Code
- Docker Desktop
- Git, and a GitHub account

If you open this repository in VS Code, it will offer to reopen it in a dev container. Accept. The container has the database client and the extensions you need (ERD Editor and Mermaid preview) already installed.

## How to work

1. Start the database from the repository folder:

   ```sh
   docker compose up -d --wait
   ```

   MariaDB listens on port 3306 (user `root`, password `root`). Adminer, a browser-based database tool, runs at <http://localhost:8080>. Log in with server `db`, user `root` and password `root`.

2. Open the unit's README and start at checkpoint 01.
3. Each checkpoint ends with a "Done when" checklist. Tick every item before moving on.
4. Commit after each checkpoint (see below).

To run SQL against the database, use `scripts/sql.sh`. It works the same way on your computer and in the dev container:

```sh
echo "SHOW DATABASES;" | scripts/sql.sh
scripts/sql.sh iceadmin < path/to/file.sql
```

To check every schema you have written, run:

```sh
scripts/validate.sh
```

It applies each `schema.sql` to a fresh database, followed by its `seed.sql` if there is one. Any file it cannot apply is reported, along with the error. Files that are still empty stubs are skipped.

## How to submit

Your instructor will send you a GitHub Classroom link. Accepting it creates your own copy of this repository. Clone that copy and do your work in it.

After each checkpoint:

```sh
git branch --show-current     # note the branch name, usually main
git add .
git commit -m "m4u2: checkpoint 01 done"
git push origin <branch>      # use the name printed by the first command
```

Do not assume your branch is called `master`. Always check it first.

Every push runs the same validation on GitHub, under the Actions tab. A red cross means one of your schemas failed. Open the run to see which file failed and why.
