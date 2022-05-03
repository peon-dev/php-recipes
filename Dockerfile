FROM php:8.1-cli as dev

ENV COMPOSER_MEMORY_LIMIT=-1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Very convenient PHP extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN mkdir /.composer \
    && chown 1000:1000 /.composer

RUN apt-get update && apt-get install -y \
    git \
    zip

RUN install-php-extensions \
    intl \
    zip \
    xdebug

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER 1000:1000


FROM dev as prod

ENV APP_ENV="prod"
ENV APP_DEBUG=0

USER root

# Unload xdebug extension by deleting config
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p /peon/var/cache && chown -R 1000:1000 /peon

USER 1000:1000
WORKDIR /peon

# Intentionally split into multiple steps to leverage docker layer caching
COPY --chown=1000:1000 composer.json composer.lock symfony.lock ./

RUN composer install --no-interaction --no-scripts

# Copy application source code
COPY --chown=1000:1000 . .

# Need to run again to trigger scripts with application code present
RUN composer install --no-interaction
