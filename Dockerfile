# Stage 1 - build frontend with Node
FROM node:18 AS nodebuild
WORKDIR /app

# copy package files and install
COPY package*.json ./
RUN npm ci

# copy rest and build
COPY . .
RUN npm run build

# Stage 2 - PHP-FPM + Nginx
FROM php:8.2-fpm

# install system deps + nginx + tools
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# install php extensions (pgsql + mysql optional)
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip bcmath

# copy composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# set working dir
WORKDIR /var/www/html

# copy application files
COPY . .

# copy built assets from node stage
COPY --from=nodebuild /app/public/build /var/www/html/public/build

# install php dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# configure php-fpm to listen on TCP 9000
RUN sed -i "s|listen = .*|listen = 9000|" /usr/local/etc/php-fpm.d/www.conf \
 && sed -i "s|;listen.owner = .*|listen.owner = www-data|" /usr/local/etc/php-fpm.d/www.conf \
 && sed -i "s|;listen.group = .*|listen.group = www-data|" /usr/local/etc/php-fpm.d/www.conf

# add nginx config and start script (we'll add these files next)
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY docker/start-container.sh /usr/local/bin/start-container.sh
RUN chmod +x /usr/local/bin/start-container.sh

# expose port 80
EXPOSE 80

# run the start script (it will start php-fpm and nginx)
CMD ["/usr/local/bin/start-container.sh"]
