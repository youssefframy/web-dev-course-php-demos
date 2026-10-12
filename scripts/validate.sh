#!/usr/bin/env bash
# Applies every non-empty schema.sql (and a sibling seed.sql, if present) to a fresh database.
#
# Usage: scripts/validate.sh [folder]     (default: modules/)
# Uses scripts/sql.sh, so DB_CMD can override the client command.
set -uo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
ROOT="$(cd "${1:-$REPO/modules}" && pwd)" || exit 2
cd "$REPO" || exit 2
SQL="$REPO/scripts/sql.sh"

if ! echo "SELECT 1;" | "$SQL" >/dev/null 2>&1; then
  echo "Cannot reach the database. Start it with: docker compose up -d --wait"
  exit 2
fi

passed=0 skipped=0 failed=0 n=0

while IFS= read -r -d '' schema; do
  rel="${schema#"$ROOT"/}"
  # A stub contains only comments and whitespace.
  if [ -z "$(perl -0pe 's{/\*.*?\*/}{}gs; s{^\s*(--|#).*$}{}mg; s{\s+}{}g' "$schema")" ]; then
    echo "SKIP $rel (empty stub)"
    skipped=$((skipped + 1))
    continue
  fi

  n=$((n + 1))
  db="v_$n"
  seed="$(dirname "$schema")/seed.sql"

  echo "DROP DATABASE IF EXISTS $db; CREATE DATABASE $db;" | "$SQL" >/dev/null 2>&1

  if ! err="$("$SQL" "$db" < "$schema" 2>&1)"; then
    echo "FAIL $rel: $err"
    failed=$((failed + 1))
  elif [ -f "$seed" ] && ! err="$("$SQL" "$db" < "$seed" 2>&1)"; then
    echo "FAIL ${rel%schema.sql}seed.sql: $err"
    failed=$((failed + 1))
  else
    echo "PASS $rel$([ -f "$seed" ] && echo ' + seed.sql')"
    passed=$((passed + 1))
  fi

  echo "DROP DATABASE $db;" | "$SQL" >/dev/null 2>&1
done < <(find "$ROOT" -name schema.sql -type f -print0 | sort -z)

echo "$passed passed, $skipped skipped, $failed failed"
[ "$failed" -eq 0 ]
