FROM php:7.2-cli-alpine

RUN set -xe \
    && apk add --no-cache --virtual .persistent-deps  postgresql-dev zlib-dev \
    && apk add --no-cache --virtual .build-deps autoconf \
    && docker-php-ext-install \
        bcmath \
        pcntl \
        pgsql \
        pdo \
        pdo_pgsql \
        mbstring \
        sysvsem \
        zip

# Iconv fix
RUN apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/testing/ --allow-untrusted
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && chmod +x /usr/local/bin/composer \
   && composer global require hirak/prestissimo \
   && composer clear-cache

COPY ./tools/* /tools/
