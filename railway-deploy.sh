#!/bin/sh

# ✅ Ensure build assets exist before proceeding
if [ ! -f "public/build/manifest.json" ]; then
  echo "❌ Error: manifest.json not found in public/build"
  exit 1
fi

# Set permissions
chmod -R 755 storage bootstrap/cache public/build

# Clear caches and generate key
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan key:generate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start Laravel
php artisan serve --host=0.0.0.0 --port=$PORT
