# Set the base image for subsequent instructions
ARG BASE_PHP_IMAGE=php:8.2
FROM $BASE_PHP_IMAGE

ARG NODE_VERSION=16

ENV IPE_GD_WITHOUTAVIF=1

COPY ./conf.d /usr/local/etc/php/conf.d

# Update OS Packages
RUN apt-get update \
  && apt-get install -y --no-install-recommends bash git curl unzip sudo lsb-release gnupg \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

# Install Necessary PHP Extensions
RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
  && chmod +x /usr/local/bin/install-php-extensions \
  && docker-php-ext-enable opcache \
  && install-php-extensions gd pdo_mysql pdo_pgsql intl zip bz2 pcntl exif bcmath xdebug \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

# Install Node
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | sudo -E bash - \ 
  && apt-get install -y --no-install-recommends nodejs npm \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

# Install Composer 
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer global require "laravel/envoy" \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

ENV PATH="/root/.composer/vendor/bin:$PATH"