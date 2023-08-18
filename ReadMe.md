# Laravel Docker Images for Gitlab CI/CD pipelines

## How To Build Images

1. Make sure to only use PHP official Images using CLI Alpine Variant

PHP 8.2 with Node 16 and Composer
```bash
user@machine:~$ docker build \
  -t sean1999/laravel-ci:8.2 \
  --build-arg BASE_PHP_IMAGE=php:8.2-cli-alpine \
  --build-arg NODE_VERSION=16 \
  --no-cache Images/php
```

PHP 8.1 with Node 16 and Composer
```bash
user@machine:~$ docker build \
  -t sean1999/laravel-ci:8.1 \
  --build-arg BASE_PHP_IMAGE=8.1 \
  --build-arg NODE_VERSION=16 \
  --no-cache /Volumes/Workspace/projects/laravel/laravel-ci-cd/images/php
```

PHP 8.0 with Node 16 and Composer
```bash
user@machine:~$ docker build \
  -t sean1999/laravel-ci:8.0 \
  --build-arg BASE_PHP_IMAGE=8.0 \
  --build-arg NODE_VERSION=16 \
  --no-cache /Volumes/Workspace/projects/laravel/laravel-ci-cd/images/php
```
