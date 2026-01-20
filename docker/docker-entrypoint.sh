#!/bin/bash

# Exit on error
set -e

# Run standard Composer install
composer install

# Waiting for MySQL
echo "Waiting for MySQL..."
sleep 10

# Run migrations and seeders
php artisan migrate --force --seed

# Generate Swagger docs
php artisan l5-swagger:generate

# Start PHP-FPM
php-fpm
