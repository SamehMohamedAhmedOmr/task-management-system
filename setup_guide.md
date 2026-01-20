# Task Management System - Setup Guide

This guide provides instructions on how to set up and run the Task Management System API in two ways:

1. **Direct Setup** (Local Environment)
2. **Docker Setup** (Containerized Environment)

---

## Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **MySQL**: 8.0 or higher
- **Docker & Docker Compose** (for Docker setup)

---

## Option 1: Direct Setup

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Environment

1. Copy `.env.example` to `.env`:
    ```bash
    cp .env.example .env
    ```
2. Generate Application Key:
    ```bash
    php artisan key:generate
    ```
3. Update `.env` with your database credentials:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

### 3. Run Migrations & Seeders

This will create the database tables and populate them with initial roles and users.

```bash
php artisan migrate --seed
```

### 4. Create Documentation (Swagger)

```bash
php artisan l5-swagger:generate
```

### 5. Start the Server

```bash
php artisan serve
```

Access the API at `http://localhost:8000`.
API Documentation: `http://localhost:8000/api/documentation`.

---

## Option 2: Docker Setup

### 1. Build and Start Containers

```bash
docker-compose up -d --build
```

### 2. Verify Installation

The application container is configured to automatically run migrations and seeders on startup.
You can check the logs to confirm:

```bash
docker-compose logs -f app
```

### 3. Access Application

- **API**: `http://localhost:8000`
- **Swagger Docs**: `http://localhost:8000/api/documentation`
- **PhpMyAdmin**: `http://localhost:8080` (Username: `laravel`, Password: `root`)

---

## Default User Accounts

The system comes pre-seeded with the following accounts:

| Role        | Email                 | Password   |
| ----------- | --------------------- | ---------- |
| **Manager** | `manager@example.com` | `password` |
| **Manager** | `admin@example.com`   | `password` |
| **User**    | `user1@example.com`   | `password` |
| **User**    | `user2@example.com`   | `password` |
| ...         | ...                   | ...        |
| **User**    | `user5@example.com`   | `password` |
