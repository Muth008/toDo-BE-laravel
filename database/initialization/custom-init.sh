#!/bin/bash
# custom-init.sh

# Wait for MySQL to be ready
until mysql -u root --password="${MYSQL_ROOT_PASSWORD}" -e ";" ; do
  echo "MySQL is unavailable - sleeping"
  sleep 1
done

# Now proceed with MySQL commands
mysql -u root --password="${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};
    CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE}_testing;
    CREATE USER IF NOT EXISTS '${MYSQL_USER_NAME}'@'%' IDENTIFIED BY '${MYSQL_USER_PASSWORD}';
    GRANT ALL PRIVILEGES ON *.* TO '${MYSQL_USER_NAME}'@'%' WITH GRANT OPTION;
    FLUSH PRIVILEGES;
EOSQL