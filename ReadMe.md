# Laravel Docker Images for Gitlab CI/CD pipelines

## How To Build Images
PHP 8.2 with Node 16 and Composer
```bash
user@machine:~$ docker build \
  -t sean1999/laravel-ci:8.2 \
  --build-arg BASE_PHP_IMAGE=8.2 \
  --build-arg NODE_VERSION=16 \
  --no-cache images/php
```
