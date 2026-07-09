# Jar of Hope - API

This is the backend API for the **Jar of Hope** application. 

## Tech Stack
- **Framework**: Laravel 13
- **Authentication**: Laravel Sanctum (Token-based API Auth)
- **Language**: PHP 8.3

## Getting Started

### Prerequisites
Make sure you have PHP 8.3+ and Composer installed.

### Setup Instructions
1. Install dependencies:
   ```bash
   composer install
   ```
2. Set up your environment variables by duplicating `.env.example` to `.env` and configuring your database credentials.
3. Generate an application key:
   ```bash
   php artisan key:generate
   ```
4. Run the database migrations (this will also create the Sanctum tokens table):
   ```bash
   php artisan migrate
   ```

### Development Server
To start the local development server, run:
```bash
php artisan serve
```
The API will be available at `http://localhost:8000`.

## Authentication
This API uses **Laravel Sanctum** to issue API tokens for frontend authentication. Users need to be authenticated via Sanctum to access protected routes.
