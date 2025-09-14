#!/bin/bash
set -e

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install JS dependencies and build
npm install
npm run build

# Run migrations (optional)
php artisan migrate --force
