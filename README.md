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

## Adding new recipes

1. Add recipe into `Peon\PhpRecipes\Recipe\Recipe` enum (`src/Recipe.php`)
2. Write config for that recipe (`src/Recipe/Config/<recipe-name>.php`)
3. Write test expectation xml (`tests/RecipesExpectedChanges/<recipe-name>.xml`)
    <small>You might need to prepare some code for it in example application (`tests/ExampleApplication`)</small>
