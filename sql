#!/usr/bin/env bash
# Runs the course tool. Try: ./sql help
# In the dev container PHP is installed, so the tool runs directly. Elsewhere it runs in the runner service.
cd "$(dirname "$0")" || exit 2
if [ -n "${SQL_RUNNER_LOCAL:-}" ]; then
  exec php runner/sql.php "$@"
fi
args=(--rm -u "$(id -u):$(id -g)")
[ -t 0 ] && [ -t 1 ] || args+=(-T)
[ -n "${SOLUTIONS:-}" ] && args+=(-v "$(cd "$SOLUTIONS" && pwd):/solutions:ro" -e SOLUTIONS=/solutions)
if ! out="$(docker compose up -d --wait db dbml 2>&1)"; then
  echo "$out"
  exit 2
fi
exec docker compose run --no-deps "${args[@]}" runner php runner/sql.php "$@"
