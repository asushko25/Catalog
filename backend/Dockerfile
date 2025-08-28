FROM php:8.2-fpm-alpine

RUN apk add --no-cache icu-dev git unzip libzip-dev postgresql-dev \
  && docker-php-ext-install intl pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
CMD ["php-fpm"]
