#!/bin/sh

# Install Node dependencies (but skip dev dependencies)
npm ci --only=production

# SKIP building assets since we already have them in Git
# npm run build

# Set proper permissions FIRST
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/build

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

# Start Laravel
php artisan serve --host=0.0.0.0 --port=$PORT