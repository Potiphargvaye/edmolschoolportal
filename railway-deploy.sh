#!/bin/sh

echo "🚀 Running Railway Deploy Script..."

# Set permissions
chmod -R 755 storage bootstrap/cache

# Clear old caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Laravel
php artisan serve --host=0.0.0.0 --port=$PORT
