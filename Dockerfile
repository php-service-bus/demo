FROM debian:jessie

RUN apt-get update && apt-get -y install apt-transport-https \
    && echo "deb https://packages.sury.org/php/ jessie main" > /etc/apt/sources.list.d/dotdeb.list \
    && apt-get -y install curl \
    && apt-get -y install supervisor \
    && apt-get -y install ca-certificates \
    && curl -sS https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        php7.1-cli \
        php7.1-apcu php7.1-apcu-bc \
        php7.1-curl \
        php7.1-json \
        php7.1-opcache \
        php7.1-zip \
        php7.1-xml \
        php7.1-dev \
        php7.1-pdo-pgsql \
        php7.1-sqlite \
        libevent-dev \
        make  libssl-dev  pkg-config \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN chmod -R 777 /var/cache && chmod -R 777 /var/log

ADD https://pecl.php.net/get/event-2.3.0.tgz /var/lib/event/event-2.3.0.tgz
RUN cd /var/lib/event/ && tar zfx event-2.3.0.tgz && cd event-2.3.0  \
    &&  phpize \
    &&  ./configure --with-event-core --with-event-extra \
    &&  make  && make install \
    && echo "extension = event.so" >> /etc/php/7.1/cli/php.ini


COPY ./docker/conf/supervisord.conf /etc
WORKDIR /usr/src/app

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
