# Database Setup with Docker

This guide explains how to set up a MySQL database using Docker and initialize it with a custom SQL script.

## Prerequisites

- Docker and Docker Compose installed on your machine.
- Copy the `.env.example` file to a new file named `.env` and update the environment variables to match your local setup.

## Structure

- `docker-compose.yml`: Docker Compose file to set up MySQL and phpMyAdmin services.
- `sql-scripts/`: Directory containing SQL scripts for database initialization.
- `custom-init.sh`: A script to customize the initialization of the MySQL database, including password replacement in SQL scripts.

## Setup

1. **Environment Variables**: Ensure your `.env` file contains the necessary environment variables (`MYSQL_ROOT_PASSWORD`, `MYSQL_DATABASE`, `MYSQL_USER_NAME` and `MYSQL_USER_PASSWORD`). These variables are used by the MySQL service and the custom initialization script.

2. **Docker Compose File**: The `docker-compose.yml` file defines the MySQL database and phpMyAdmin services. It uses the environment variables for configuration and mounts the `/initialization/custom-init.sh` script for database initialization. You can find the Docker Compose file in the [database directory](docker-compose.yml).

3. **SQL Scripts**: Place your SQL initialization scripts in the `sql-scripts/` directory. The `custom-init.sh` script will execute these scripts and replace any password placeholders with the actual password set in the `.env` file.

## Running the Services

To start the services, navigate to the directory containing your `docker-compose.yml` file and run:

```sh
docker-compose up -d
```

This command will start the MySQL database and phpMyAdmin services in detached mode.

#### Accessing phpMyAdmin

- **URL**: http://localhost:8080/
- **Server**: mysql_db
- **Username**: The value of `MYSQL_USER_NAME` set in your `.env` file.
- **Password**: The value of `MYSQL_USER_PASSWORD` set in your `.env` file.

#### Stopping the Services

To stop and remove the containers, networks, and volumes created by `docker-compose up`, run:

```sh
docker-compose down
```

### Customizing the Setup

- **MySQL Version**: Change the image tag for the `mysql_db` service in the `docker-compose.yml` file to use a different MySQL version.
- **Environment Variables**: The `.env` file allows you to customize MySQL configuration easily.
- **phpMyAdmin Configuration**: Adjust the environment section for the phpmyadmin service in the `docker-compose.yml` file to change phpMyAdmin settings.

For more detailed information on the Docker Compose configuration, refer to the official Docker documentation.