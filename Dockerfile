FROM ghcr.io/peon-dev/php:recipes-main

ENV APP_ENV="prod"
ENV APP_DEBUG=0

USER root

RUN mkdir /peon && chown -R 1000:1000 /peon

USER 1000:1000
WORKDIR /peon

# Intentionally split into multiple steps to leverage docker layer caching
COPY --chown=1000:1000 composer.json composer.lock ./

RUN composer install --no-interaction --no-scripts

# Copy application source code
COPY --chown=1000:1000 . .

# Need to run again to trigger scripts with application code present
RUN composer install --no-interaction
