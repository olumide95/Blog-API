# Blog API

## Requirements

-   PHP >= 8.1
-   Composer
-   Laravel

## Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Set up environment variables: `cp .env.example .env` and modify as needed
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Seed the database: `php artisan db:seed`
7. Serve the application: `php artisan serve`
8. API documentation can be found at : `/api/documentation`

## Getting Admin Token

Email: admin@admin.com
Password: password

## Running Tests

Run the PHPUnit tests: : `php artisan test`

## Automating Post Publishing (Hourly)

`php artisan schedule:run >> /dev/null 2>&1`

## Trigger Post Publishing Command Manually

`php artisan app:publish-scheduled-posts`

## Regenrating API Documentation

`php artisan l5-swagger:generate`
