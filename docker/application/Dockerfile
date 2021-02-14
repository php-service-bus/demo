FROM php:8.0-cli-alpine

RUN set -xe \
    && apk update \
    && apk add oniguruma-dev libpq postgresql-dev libevent-dev autoconf zlib-dev g++ libtool make libzip-dev git \
    # Iconv fix
    && apk add --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ gnu-libiconv \
    && docker-php-ext-install \
        bcmath \
        pcntl \
        mbstring \
        sysvsem \
        zip \
        # escape bytea string
        pgsql \
        # sockets
        && docker-php-ext-install sockets

RUN wget https://github.com/FriendsOfPHP/pickle/releases/download/v0.7.0/pickle.phar \
    && mv pickle.phar /usr/local/bin/pickle \
    && chmod +x /usr/local/bin/pickle

RUN pickle install event \
    && docker-php-ext-enable event \
    && mv /usr/local/etc/php/conf.d/docker-php-ext-event.ini /usr/local/etc/php/conf.d/docker-php-ext-zz-event.ini \
    && pickle install raphf \
    && docker-php-ext-enable raphf \
    && pickle install pq \
    && echo "extension=pq.so" > /usr/local/etc/php/conf.d/pq.ini \
    && rm -rf /tmp/* /var/cache/apk/*

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && chmod +x /usr/local/bin/composer \
   && composer clear-cache

COPY ./tools/* /tools/
