FROM php:8.3-fpm-alpine

RUN apk add --no-cache supervisor curl jq bash zip unzip

COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions opcache pdo pdo_pgsql http sockets pcntl

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p runtime/logs

RUN composer install --no-dev --optimize-autoloader

COPY ./supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["./entrypoint.sh"]
