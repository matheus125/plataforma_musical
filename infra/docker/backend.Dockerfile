FROM php:8.4-cli-alpine

RUN apk add --no-cache bash git unzip libpq-dev \
  && docker-php-ext-install pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY backend/ .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8000
