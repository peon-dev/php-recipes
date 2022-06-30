# Peon official PHP recipes

Set of PHP recipes for [peon.dev](https://github.com/peon-dev/peon)

## Usage

Expects mounted, built PHP application in `/app` directory. 

```
bin/run-recipe [options] <recipeName> <paths>...

Arguments:
  recipeName             
  paths                  

Options:
      --timeout=TIMEOUT  
  -h, --help             Display help for the given command. When no command is given display help for the bin/run-recipe command
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi|--no-ansi   Force (or disable --no-ansi) ANSI output
  -n, --no-interaction   Do not ask any interactive question
  -e, --env=ENV          The Environment name. [default: "dev"]
      --no-debug         Switches off debug mode.
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

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
