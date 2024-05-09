# Set the base image for subsequent instructions
ARG BASE_PHP_IMAGE=sean1999/base-php-ci:8.3

FROM $BASE_PHP_IMAGE

# An ARG declared before a FROM is outside of a build stage, so it can't be used in any instruction after a FROM. 
ARG NODE_VERSION=22

ENV NVM_DIR /root/.nvm

ENV PATH="/root/.nvm:/root/.composer/vendor/bin:$PATH"

# Install NVM and Node.js
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash \
  && . $NVM_DIR/nvm.sh \
  && nvm install ${NODE_VERSION} \
  && nvm alias default ${NODE_VERSION} \
  && nvm use default \
  && nvm cache clear \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://bun.sh/install | bash \
  && echo 'export PATH="$PATH:/usr/local/bun/bin"' >> ~/.bashrc \
  && . ~/.bashrc \
  && apt-get clean autoclean \
  && apt-get autoremove --yes \
  && rm -rf /var/lib/apt/lists/*