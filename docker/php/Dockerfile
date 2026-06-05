FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 laravel && \
    useradd -u 1000 -g laravel -m -s /bin/bash laravel

ENV HOME=/home/laravel

WORKDIR /var/www/html
