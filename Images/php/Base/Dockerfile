# Set the base image for subsequent instructions
ARG BASE_PHP_IMAGE=php:8.0

FROM $BASE_PHP_IMAGE

COPY ./conf.d /usr/local/etc/php/conf.d

ENV PATH="/root/.composer/vendor/bin:$PATH"

# Update OS Packages
RUN apt-get update -qq -y \
  && apt-get install -q -y --no-install-recommends bash git curl unzip sudo lsb-release gnupg openssh-client \
  && apt-get -q clean autoclean \
  && apt-get -q autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

# Install Necessary PHP Extensions
RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
  && chmod +x /usr/local/bin/install-php-extensions \
  && docker-php-ext-enable opcache \
  && IPE_GD_WITHOUTAVIF=1; IPE_ICU_EN_ONLY=1; IPE_SKIP_CHECK=1; IPE_DEBUG=0 install-php-extensions gd pdo_mysql pdo_pgsql intl zip bz2 pcntl exif bcmath xdebug \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

# Install Composer 
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer global require "laravel/envoy" \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*