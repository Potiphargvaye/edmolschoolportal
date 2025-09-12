#!/bin/sh

# Install Node dependencies
npm ci --only=production

# Build Vite assets for production
npm run build


# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Fix permissions (important for Railway)
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/build

# Start Laravel
php artisan serve --host=0.0.0.0 --port=$PORT