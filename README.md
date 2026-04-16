# CHROMA Lifestyle and Concept Store

CHROMA Lifestyle and Concept Store is a Laravel-based management platform for a public-facing website and an internal admin panel. It supports class and event browsing, appointment bookings, student registration, payments, media management, and operational reporting.

## Features

- Public website with home, classes, sessions, events, gallery, contact, and registration pages.
- Appointment booking calendar with booking details, stats, and admin approval flow.
- Admin dashboard with at-a-glance unpaid student and teacher summaries.
- Student, teacher, class, session, and event management.
- Image gallery and image category management.
- Student payment, teacher payment, and instructor payment workflows with receipt generation.
- Reports for user payments and downloadable report data.
- Contact form email handling.

## Tech Stack

- Laravel 10
- PHP 8.1+
- Vite
- Laravel Sanctum
- Dompdf for PDF receipts and exports

## Requirements

- PHP 8.1 or newer
- Composer
- Node.js and npm
- A supported database server such as MySQL or MariaDB

## Installation

1. Clone the repository and enter the project directory.
2. Install PHP dependencies with `composer install`.
3. Install frontend dependencies with `npm install`.
4. Copy `.env.example` to `.env` and configure your application name, database, mail, and URL settings.
5. Generate an application key with `php artisan key:generate`.
6. Run the database migrations and seeders with `php artisan migrate --seed`.
7. Build the frontend assets with `npm run build`, or use `npm run dev` while developing.
8. Start the application with `php artisan serve`.

Change this credential immediately after first login.

## Common Commands

```bash
php artisan serve
php artisan migrate --seed
php artisan route:list
npm run dev
npm run build
```

## Project Structure

- `app/Http/Controllers` contains the application controllers for frontend and backend modules.
- `app/Models` contains the Eloquent models for students, teachers, classes, sessions, events, payments, and bookings.
- `resources/views/frontend` contains the public website views.
- `resources/views/backend` contains the admin dashboard and management views.
- `routes/web.php` defines the public pages, authentication, admin routes, payments, reports, and calendar endpoints.
- `database/seeders` contains the initial database seed data.

## Notes

- The app name is configured in `config/app.php` through the `APP_NAME` environment variable.
- Receipts and report exports depend on the PDF tooling installed through Composer.
- Contact form and notification workflows rely on your mail configuration being valid in `.env`.

## License

This project is released under the MIT License.