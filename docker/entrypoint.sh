#!/bin/sh
set -e

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-task_management}"
DB_USERNAME="${DB_USERNAME:-laravel}"
DB_PASSWORD="${DB_PASSWORD:-root}"
RUN_SEED="${RUN_SEED:-true}"

echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
for i in $(seq 1 60); do
    if php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" >/dev/null 2>&1; then
        echo "Database is ready."
        break
    fi
    echo "Database not ready yet... (${i}/60)"
    sleep 2
done

echo "Running migrations..."
php artisan migrate --force

if [ "${RUN_SEED}" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

exec "$@"
