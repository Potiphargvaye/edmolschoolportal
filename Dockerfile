# Stage 1: Node build
FROM node:18 as nodebuild
WORKDIR /app

# Copy only package files first
COPY package*.json ./
RUN npm install

# Copy rest of the app
COPY . .

# Build frontend assets
RUN npm run build

# Stage 2: PHP
FROM php:8.2-cli as phpbuild

WORKDIR /app

# Install system dependencies (example for Laravel)
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip curl \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app code
COPY . .

# Copy built assets from Node build stage
COPY --from=nodebuild /app/public/build /app/public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader
# At the very bottom of Dockerfile
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]


