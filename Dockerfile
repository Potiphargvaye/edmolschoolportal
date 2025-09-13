# Stage 1: Node build
FROM node:18 as nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2: PHP + Nginx
FROM php:8.2-fpm as phpbuild
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip curl nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app code
COPY . .

# Copy built assets
COPY --from=nodebuild /app/public/build /app/public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy config files
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start-container.sh /start-container.sh
RUN chmod +x /start-container.sh

EXPOSE 8080

CMD ["/start-container.sh"]
