#!/bin/ash

composer install -o --no-scripts --no-dev --no-suggest
/usr/bin/supervisord -n -c /etc/supervisord.conf
