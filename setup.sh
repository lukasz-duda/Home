#!/bin/bash
# PHP
sudo apt-get install php-xml php-json php-mbstring php-mysql

# Composer
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# Apache
sudo apt install libapache2-mod-php
sudo ln -s /home/lukasz/Pulpit/CatFeedingAssistant /var/www/html/assistant