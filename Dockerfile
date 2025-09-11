# Stage 1 - Build assets with Node
FROM node:18 as build

WORKDIR /app

# Copy only package files first (for caching)
COPY package.json package-lock.json* ./

RUN npm install

# Copy the rest of the app
COPY . .

# Build assets for production
RUN npm run build


# Stage 2 - PHP + Apache for Laravel
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy app source from build stage
COPY --from=build /app ./

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Run Apache
CMD ["apache2-foreground"]
