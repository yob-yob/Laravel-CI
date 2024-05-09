# Set the base image for subsequent instructions
ARG BASE_PHP_IMAGE=sean1999/base-php-ci:8.3
ARG NODE_VERSION=22

FROM $BASE_PHP_IMAGE

COPY ./conf.d /usr/local/etc/php/conf.d

ENV NVM_DIR /root/.nvm

ENV PATH="$HOME/.nvm:/root/.composer/vendor/bin:/usr/local/bun/bin:$PATH"

# Install NVM
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash \
  && . $NVM_DIR/nvm.sh \
  && nvm install ${NODE_VERSION} \ 
  && nvm alias default ${NODE_VERSION} \
  && nvm use default

RUN  curl -fsSL https://bun.sh/install | bash \
  && echo 'export PATH="$PATH:/usr/local/bun/bin"' >> ~/.bashrc \
  && . ~/.bashrc \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*