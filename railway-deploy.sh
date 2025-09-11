#!/bin/sh

# Install Node dependencies for Linux
npm ci

# Build Vite assets
npm run build

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Start Laravel
php artisan serve --host=0.0.0.0 --port=$PORT
