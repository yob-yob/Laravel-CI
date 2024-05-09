# Laravel Docker Images for Gitlab CI/CD pipelines

Hi, this are my Docker Images that I personally use to run my Gitlab CI/CD Pipeline for Laravel Projects.

## How To Build Images

1. Build the Base-Image First
2. Make sure to only use PHP official Images using CLI Alpine Variant
3. Specify a Node Version
4. Build step is automatically done using github actions (WIP)

### Build the base image

```console
docker build -t sean1999/base-php-ci:8.3 \
  --build-arg BASE_PHP_IMAGE=php:8.3  \
  --platform=linux/amd64 \
  --no-cache Images/php/8.3  2>&1 | tee Logs/8.3-build.log
docker push sean1999/base-php-ci:8.3
```

### Build the image

PHP 8.3 with Node 22 and Composer (Change versions as needed)

```console
docker build -t sean1999/laravel-ci:8.3 \
  --build-arg BASE_PHP_IMAGE=php:8.3  \
  --build-arg NODE_VERSION=22 \
  --platform=linux/amd64 \
  --no-cache Images/php  2>&1 | tee Logs/8.3-build.log
docker push sean1999/laravel-ci:8.3
```

## Roadmap

1. For now this repository only handles Laravel Projects but I will soon create a docker image that will also be perfect for Other Frameworks
   1. Svelte & Vue
