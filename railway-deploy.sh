#!/bin/sh

# Install Node dependencies
echo "Installing Node.js dependencies..."
npm install --legacy-peer-deps

# Build frontend assets for production
echo "Building assets..."
npm run build

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Start Laravel
echo "Starting Laravel..."
php artisan serve --host=0.0.0.0 --port=$PORT