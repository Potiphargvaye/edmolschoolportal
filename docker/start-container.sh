#!/bin/sh
set -e

echo "=== Container start script ==="
echo "Running Laravel prep tasks..."

# ensure storage/cache permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

# clear caches (don't fail the container if something goes wrong)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# run migrations (will use runtime env vars provided by Railway)
echo "Running migrations..."
php artisan migrate --force || echo "Migrations failed (continuing)..."

# optional: rebuild caches (safe to attempt)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# start php-fpm as daemon
echo "Starting php-fpm..."
php-fpm -D

# start nginx in foreground (this keeps the container running)
echo "Starting nginx..."
nginx -g 'daemon off;'
