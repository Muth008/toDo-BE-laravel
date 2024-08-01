# ToDo Application

Welcome to the ToDo application! This project is a simple basic task management application, inside categories, with defined priorities, statuses, and due dates. It consists of a backend Laravel application and a MySQL database.

## Overview

The ToDo application is divided into two main parts:

- **Database**: A MySQL database to store all application data.
- **Backend**: A Laravel application providing a REST API for managing tasks and categories.

## Getting Started

### Prerequisites

- PHP (version 8.2 or higher)
- Composer
- MySQL database
- Docker (optional, for running the database in a container)

### Setup

#### Clone the Repository

```sh
git clone https://github.com/Muth008/toDo-BE-laravel
cd toDo-BE-laravel
```

#### Database

1. Ensure Docker is installed and running.
2. Navigate to the `database/` directory.
3. Copy the `.env.example` file to `.env` and update the environment variables.
4. Run `docker-compose up -d` to start the database in a Docker container (optional).
5. Apply migrations and seed the database as described in the backend setup.

For detailed instructions, refer to the database README: `database/README.md`.

#### Backend

1. Navigate to the `backend/` directory.
2. Copy the `.env.example` file to `.env` and update the environment variables, especially the database credentials.
3. Run `composer install` to install dependencies.
4. Run `php artisan migrate` to create the database schema.
5. Run `php artisan key:generate` to generate the application key.
6. Start the application in dev mode with `php artisan serve`.

For detailed instructions, refer to the backend README: `backend/README.md`.

## Running the Application

After setting up the backend and database, you can start the backend application as described in the backend section. Ensure the database is running and accessible.

## Testing

Backend tests can be run with `php artisan test` in the `backend/` directory.

## API Documentation

The backend REST API documentation is available at `http://<APP_URL>:<PORT>/api/documentation` (replace `<APP_URL>` and `<PORT>` with your configured values). You can also visit homepage of backend app, where is link to documentation.

## License

This project is licensed under the MIT License - see the LICENSE.
