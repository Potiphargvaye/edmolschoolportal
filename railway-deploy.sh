#!/bin/sh

# Set permissions for pre-built assets
chmod -R 755 storage bootstrap/cache public/build

# Clear caches and generate key
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan key:generate --force




# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT