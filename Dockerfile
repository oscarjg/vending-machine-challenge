FROM composer:latest AS composer

FROM php:7.4-fpm-alpine

RUN apk --update add --virtual acl make build-base openssl-dev autoconf && \
    pecl install mongodb && \
    apk del build-base openssl-dev autoconf && \
    docker-php-ext-enable mongodb

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative

WORKDIR /application

ARG APP_ENV=prod

COPY . .

RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest && \
	composer clear-cache

COPY ./docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
