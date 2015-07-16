#!/usr/bin/env bash

echo "Istalling php"

rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
yum install -y php56w-common php56w-opcache php56w-mbstring php56w-devel php56w-xml php56w-xmlrpc php56w-pear php56w-pecl-xdebug openssl-devel gcc

no | pecl install mongo

yes | cp -rf /vagrant/vagrant/provision/files/php.ini /etc/php.ini
