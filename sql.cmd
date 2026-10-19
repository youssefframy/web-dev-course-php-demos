@echo off
rem Runs the course tool on Windows. Try: sql help
cd /d "%~dp0"
docker compose up -d --wait db dbml >nul 2>&1 || (docker compose up -d --wait db dbml & exit /b 2)
docker compose run --rm --no-deps runner php runner/sql.php %*
