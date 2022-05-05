# Peon official PHP recipes

Set of PHP recipes for [peon.dev](https://github.com/peon-dev/peon)

## Usage

Expects mounted, built PHP application in `/app` directory. 

`bin/run-recipe <recipe-name>`

## Xdebug

Create `docker-compose.override.yml` with following content (tweak for your needs):
```yaml
version: "3.7"
services:
    php:
        environment:
            XDEBUG_CONFIG: "client_host=192.168.64.1"
            PHP_IDE_CONFIG: "serverName=peon"
        volumes:
          - ./.docker/php/xdebug.ini:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
```