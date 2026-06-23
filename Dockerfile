FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install     --no-dev     --no-interaction     --no-progress     --prefer-dist     --optimize-autoloader     --no-scripts

FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json ./
RUN npm install
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./vite.config.js
RUN npm run build

FROM php:8.5-cli AS runtime
RUN apt-get update && apt-get install -y     git     unzip     zip     curl     libzip-dev     libpng-dev     libonig-dev     libxml2-dev     && docker-php-ext-install         pdo         pdo_mysql         mbstring         exif         pcntl         bcmath         zip     && apt-get clean     && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY docker/railway/start.sh /usr/local/bin/start-railway

RUN chmod +x /usr/local/bin/start-railway     && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache     && chown -R www-data:www-data /var/www/html

ENV APP_ENV=production
ENV LOG_CHANNEL=stderr
ENV PHP_CLI_SERVER_WORKERS=4

EXPOSE 8080

USER www-data
CMD ["/usr/local/bin/start-railway"]
