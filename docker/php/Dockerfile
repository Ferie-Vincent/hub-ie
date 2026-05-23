FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git curl zip unzip libpng-dev libzip-dev postgresql-dev icu-dev \
    oniguruma-dev nodejs npm chromium chromium-chromedriver

RUN docker-php-ext-install \
    pdo pdo_pgsql pgsql \
    mbstring zip gd intl bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    CHROME_PATH=/usr/bin/chromium-browser
