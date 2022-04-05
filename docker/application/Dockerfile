FROM php:8.1-cli-alpine

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

# https://github.com/phpinnacle/ext-buffer
RUN echo "b9093d98bad023c3816cb623f8029033f83ca7e0" \
    && git clone https://github.com/phpinnacle/ext-buffer.git \
    && cd ext-buffer \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=buffer.so" > /usr/local/etc/php/conf.d/buffer.ini

RUN pickle install event \
    && docker-php-ext-enable event \
    && mv /usr/local/etc/php/conf.d/docker-php-ext-event.ini /usr/local/etc/php/conf.d/docker-php-ext-zz-event.ini \
    && pickle install raphf \
    && docker-php-ext-enable raphf \
    && pickle install pq \
    && echo "extension=pq.so" > /usr/local/etc/php/conf.d/pq.ini \
    && rm -rf /tmp/* /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
   && chmod +x /usr/local/bin/composer \
   && composer clear-cache

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY ./tools/* /tools/
