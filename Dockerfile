FROM php:8.1-cli as dev

ENV COMPOSER_MEMORY_LIMIT=-1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Very convenient PHP extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN mkdir /.composer \
    && chown 1000:1000 /.composer

RUN apt-get update && apt-get install -y \
    git \
    # gearman dependencies:
    libgearman-dev \
    libevent-dev \
    uuid

# Not available via docker-php-extension-installer yet
# see https://github.com/mlocati/docker-php-extension-installer/issues/495
RUN pecl install gearman

RUN install-php-extensions \
    amqp \
    apcu \
    ast \
    bcmath \
    blackfire \
    bz2 \
    calendar \
    csv \
    dba \
    decimal \
    ds \
    enchant \
    ev \
    event \
    excimer \
    exif \
    ffi \
    gd \
    geospatial \
    gettext \
    gmp \
    gnupg \
    grpc \
    http \
    igbinary \
    imagick \
    imap \
    inotify \
    intl \
    json_post \
    ldap \
    luasandbox \
    lzf \
    mailparse \
    maxminddb \
    mcrypt \
    memcache \
    memcached \
    memprof \
    mongodb \
    msgpack \
    mysqli \
    oauth \
    oci8 \
    odbc \
    opcache \
    openswoole \
    parle \
    pcntl \
    pcov \
    pdo_firebird \
    pdo_mysql \
    pdo_oci \
    pdo_odbc \
    pdo_pgsql \
    pdo_sqlsrv \
    pgsql \
    pspell \
    raphf \
    rdkafka \
    redis \
    seaslog \
    shmop \
    smbclient \
    snmp \
    soap \
    sockets \
    spx \
    sqlsrv \
    ssh2 \
    sysvmsg \
    sysvsem \
    sysvshm \
    tidy \
    timezonedb \
    uploadprogress \
    uuid \
    vips \
    xdebug \
    xhprof \
    xlswriter \
    xmldiff \
    xmlrpc \
    xsl \
    yac \
    yaml \
    yar \
    zephir_parser \
    zip \
    zookeeper \
    zstd

USER 1000:1000


FROM dev as prod

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
