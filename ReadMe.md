# Laravel Docker Images for Gitlab CI/CD pipelines

Hi, this are my Docker Images that I personally use to run my Gitlab CI/CD Pipeline for Laravel Projects.

## How To Build Images

1. Make sure to only use PHP official Images using CLI Alpine Variant
2. Specify a Node Version
3. Build step is automatically done using github actions (WIP)

PHP 8.2 with Node 16 and Composer
```bash
docker build \
  -t sean1999/laravel-ci:8.2 \
  --build-arg BASE_PHP_IMAGE=php:8.2-cli-alpine \
  --build-arg NODE_VERSION=16 \
  --no-cache Images/php
```

PHP 8.1 with Node 16 and Composer
```bash
docker build \
  -t sean1999/laravel-ci:8.1 \
  --build-arg BASE_PHP_IMAGE=php:8.1-cli-alpine \
  --build-arg NODE_VERSION=16 \
  --no-cache Images/php
```

PHP 8.0 with Node 16 and Composer
```bash
docker build \
  -t sean1999/laravel-ci:8.0 \
  --build-arg BASE_PHP_IMAGE=php:8.0-cli-alpine \
  --build-arg NODE_VERSION=16 \
  --no-cache Images/php
```

## Roadmap

1. For now this repository only handles Laravel Projects but I will soon create a docker image that will also be perfect for Other Frameworks
   1. Svelte & Vue
2. 