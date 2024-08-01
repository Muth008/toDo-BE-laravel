# ToDo Application - Backend

This is the backend part of the ToDo application, built with Laravel. It provides a REST API for managing tasks and categories.

## Prerequisites

- PHP (version 8.2 or higher)
- Composer
- MySQL database
- Docker (optional, for running the database in a container)

### Server Requirements

The Laravel framework has a few system requirements. You should ensure that your web server has the following minimum PHP version and extensions:

- PHP >= 8.2
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Setup

### 1. Environment Configuration

Copy the `.env.example` file to `.env` and update the environment variables, especially the database credentials.

```sh
cp .env.example .env
```

Edit the `.env` file to match your environment settings.

### 2. Install Dependencies

Run the following command to install the required dependencies:

```sh
composer install
```

### 3. Generate Application Key

Generate the application key:

```sh
php artisan key:generate
```

### 4. Run Migrations

Run the database migrations to create the necessary tables:

```sh
php artisan migrate
```

### 5. Seed the Database

(Optional) Seed the database with initial data:

```sh
php artisan db:seed
```

## Development

### Running the Application

To start the application in development mode, use the following command:

```sh
php artisan serve
```

The application will be available at `http://localhost:8000`.

### Running Tests

Before running the unit tests, it is necessary to create a .env.testing file with the configuration of the testing database. This ensures that tests run against a separate database environment.

To run the tests, use the following command:

```sh
php artisan test
```

### Code Style

Ensure your code follows the PSR-12 coding standard. You can use PHP CS Fixer to automatically fix code style issues:

```sh
composer require --dev friendsofphp/php-cs-fixer
vendor/bin/php-cs-fixer fix .
```

## Production

### Deployment

1. Clone the Repository: Clone the repository to your production server.
2. Environment Configuration: Set up the `.env` file with your production environment settings.
3. Install Dependencies: Run `composer install --no-dev` to install dependencies without development packages.
4. Generate Application Key: Run `php artisan key:generate` to generate the application key.
5. Run Migrations: Run `php artisan migrate --force` to apply the database migrations.
6. Optimize: Run the following commands to optimize the application for production:
    `php artisan config:cache`
    `php artisan route:cache`
    `php artisan view:cache`

For more detailed information on deploying Laravel applications, refer to the [official Laravel documentation](https://laravel.com/docs/11.x/deployment).

### Setting Up a Web Server

Configure your web server (Nginx, Apache, etc.) to serve the Laravel application. Ensure the web server points to the `public` directory of the Laravel application.

## API Documentation

The backend REST API documentation is available at `http://<APP_URL>:<PORT>/api/documentation` (replace `<APP_URL>` and `<PORT>` with your configured values). You can also visit homepage of backend app, where is link to documentation.