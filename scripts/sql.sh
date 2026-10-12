#!/usr/bin/env bash
# Runs the MariaDB client against the course database. Arguments go straight to the client.
#   scripts/sql.sh iceadmin < file.sql
#   echo "SHOW DATABASES;" | scripts/sql.sh
# DB_CMD overrides the client command (the dev container and CI set it).
cd "$(dirname "$0")/.." || exit 2
# shellcheck disable=SC2086  # DB_CMD is a command line and must be split.
exec ${DB_CMD:-docker compose exec -T db mariadb -uroot -proot} "$@"
