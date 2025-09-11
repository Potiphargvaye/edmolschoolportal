#!/bin/sh

# Run migrations automatically on deployment
echo "Running database migrations..."
php artisan migrate --force

echo "Deployment finished successfully!"
