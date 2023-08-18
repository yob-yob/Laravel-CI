# Set the base image for subsequent instructions
ARG BASE_PHP_IMAGE=php:8.2
FROM $BASE_PHP_IMAGE

ARG NODE_VERSION=16

# Update packages
RUN apk update && apk upgrade \
  && apk add --update-cache \
    bash git curl libmcrypt-dev jpeg-dev libjpeg-turbo-dev libpng-dev \
    freetype-dev libbz2 libzip-dev zlib-dev \
  && rm -rf /var/cache/apk/*
  
RUN docker-php-ext-configure gd \
  && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd \
  && docker-php-ext-install pdo_mysql zip \
  && curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer global require "laravel/envoy" \
  && curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.4/install.sh | bash \
  && source ~/.nvm/nvm.sh \
  && nvm install ${NODE_VERSION} \
  && nvm alias default ${NODE_VERSION}

  
  
