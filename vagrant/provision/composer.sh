#!/usr/bin/env bash
php -r "readfile('https://getcomposer.org/installer');" | php
mv composer.phar /usr/local/bin/composer
# cd /vagrant
# /usr/local/bin/composer install
