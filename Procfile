release: php artisan migrate --force --seed
web: bash -c "composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan storage:link || true && php artisan serve --host=0.0.0.0 --port=$PORT"
