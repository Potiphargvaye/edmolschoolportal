#!/bin/sh
set -e

# Permissions
chmod -R 755 storage bootstrap/cache

# Laravel setup
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true



# Cache configs for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Supervisor (manages php-fpm + nginx)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
