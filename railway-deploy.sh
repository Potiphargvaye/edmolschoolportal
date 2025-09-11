#!/bin/sh

# Run database migrations (if needed)
php artisan migrate --force

# Clear caches so Laravel sees the new assets
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Serve the app
php artisan serve --host=0.0.0.0 --port=$PORT
