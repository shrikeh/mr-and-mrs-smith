ARG PHP_IMAGE_APP_VERSION=8.3.14-cli-bookworm
ARG APPLICATION_DIR=/main

FROM "php:${PHP_IMAGE_APP_VERSION}" AS base
RUN apt update
RUN apt install -y libyaml-dev libicu-dev libsodium-dev
RUN pecl install -o -f ds pcntl posix yaml
RUN docker-php-ext-configure intl
RUN docker-php-ext-install bcmath intl sockets
RUN docker-php-ext-enable opcache ds bcmath yaml intl

FROM base AS shrikeh
ARG GITHUB_OAUTH
ARG APPLICATION_DIR=/main
ARG LOCAL_BIN_PATH=/usr/local/bin
ENV APP_ENV=dev

COPY --from=composer:latest /usr/bin/composer "${LOCAL_BIN_PATH}/composer"

RUN chmod +x "${LOCAL_BIN_PATH}/composer"

RUN apt install -y git openssh-client unzip

WORKDIR ${APPLICATION_DIR}

# Copy in only the files and directories needed for the app from the working directory
# This is controlled by the .dockerignore in the root directory
ADD ./ ./

# Get hold of gomplate (https://docs.gomplate.ca) to template out the files we need
COPY --from=hairyhenderson/gomplate:stable /gomplate /bin/gomplate
ADD dev/docker/services/app/templates /auth

RUN /bin/gomplate --input-dir="/auth" --output-map='./{{ .in | strings.ReplaceAll ".tmpl" "" }}'

RUN ${LOCAL_BIN_PATH}/composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy only the files from before, leaving before dependencies such as composer and git
FROM base AS app
ARG APPLICATION_DIR=/main
ARG LOCAL_BIN_PATH=/usr/local/bin

COPY --from=composer:latest /usr/bin/composer "${LOCAL_BIN_PATH}/composer"
RUN chmod +x "${LOCAL_BIN_PATH}/composer"

COPY --from=shrikeh /root/.composer/ /root/.composer/

WORKDIR /cache
VOLUME /cache

RUN chown root:root /cache
RUN chmod 0777 /cache

WORKDIR ${APPLICATION_DIR}
VOLUME ${APPLICATION_DIR}
COPY --from=shrikeh ${APPLICATION_DIR} ${APPLICATION_DIR}

RUN composer validate
RUN chown -R www-data:www-data ${APPLICATION_DIR}

RUN vendor/bin/rr get --location ${LOCAL_BIN_PATH}
RUN chmod +x "${LOCAL_BIN_PATH}/rr"

ENTRYPOINT /usr/local/bin/rr serve

FROM app AS dev
ARG APPLICATION_DIR=/main
ARG LOCAL_BIN_PATH=/usr/local/bin
ENV XDEBUG_MODE=coverage
RUN pecl install xdebug
RUN apt install -y openssh-client git unzip
RUN docker-php-ext-enable xdebug

WORKDIR ${APPLICATION_DIR}

ENTRYPOINT ${LOCAL_BIN_PATH}/composer test

FROM dev AS local
ARG APPLICATION_DIR=/main
ARG LOCAL_BIN_PATH=/usr/local/bin
WORKDIR ${APPLICATION_DIR}
RUN ${LOCAL_BIN_PATH}/composer install --no-interaction --prefer-install=auto -vvv
ENTRYPOINT /usr/local/bin/rr serve -c .rr.dev.yaml
