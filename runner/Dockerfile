# The ./sql runner: PHP CLI with PDO for MariaDB, plus the MariaDB client for loading and dumping files.
FROM php:8.3.35-cli-trixie
RUN apt-get update \
 && apt-get install -y --no-install-recommends mariadb-client \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install pdo_mysql
WORKDIR /course
