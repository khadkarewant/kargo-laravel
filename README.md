# Kargo Laravel

Kargo Laravel is a cargo request management system built with Laravel.

This project demonstrates backend architecture including authentication, data ownership, authorization, and structured Git workflow.

---

## Features

- Laravel Breeze authentication (login, register, logout)
- Service request CRUD
- Request ownership (users only see their own requests)
- Authorization protection (403 on unauthorized access)
- Auth-protected routes
- Professional Git workflow (main → dev → feature branches)

---

## Tech Stack

- PHP
- Laravel
- MySQL
- Blade
- Tailwind (via Laravel Breeze)

---

## Development Timeline

1. Initial Laravel project setup
2. Service request CRUD module
3. Authentication with Laravel Breeze
4. User request ownership and authorization

---

## Setup

Clone the repository:

```bash
git clone https://github.com/khadkarewant/kargo-laravel
cd kargo-laravel
```

Install dependencies:

```bash
composer install
```

Create environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure database in `.env`, then run migrations:

```bash
php artisan migrate
```

Start development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## Author

Rewant Khadka  
Backend development project built as part of Laravel backend training.