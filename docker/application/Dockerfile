FROM php:7.2-cli-alpine

RUN apk add --no-cache --virtual .persistent-deps \
    bash \
    ca-certificates \
    coreutils \
    cyrus-sasl-dev \
	dpkg-dev dpkg \
	gcc \
	libc-dev \
	libressl \
	linux-headers \
	make \
	perl \
	perl-utils \
    tar \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    pcre-dev \
    make \
    git \
    pkgconf \
    re2c\
    zlib-dev \
    libpng-dev \
    icu-dev \
    # PostgreSQL
    postgresql-dev \
    # RabbitMQ
    rabbitmq-c-dev

# Base php extensions
RUN set -xe \
    && apk add --no-cache --virtual .build-deps autoconf \
    && docker-php-ext-install \
        bcmath \
        intl \
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

# Another php extensions
RUN pecl install amqp-1.9.3 \
    && echo "extension = amqp.so" >> /usr/local/etc/php/php.ini

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && chmod +x /usr/local/bin/composer \
   && composer global require hirak/prestissimo \
   && composer clear-cache

# Install supervisord
RUN apk add --no-cache --virtual .persistent-deps supervisor
COPY ./conf/supervisord.conf /etc/

RUN apk del \
    .build-deps \
    dpkg-dev dpkg \
    gcc \
    linux-headers \
    make \
    perl \
    perl-utils \
    tar \
    autoconf \
    cmake \
    g++ \
    gcc \
    && rm -rf /tmp/*

COPY ./tools/* /tools/
