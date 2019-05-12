#!/bin/bash
# PHP
sudo apt-get install php-xml php-json php-mbstring php-mysql

# Composer
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# Apache
sudo apt install libapache2-mod-php
sudo ln -s `pwd` /var/www/html/HomeAssistant

# MySQL
mysql_config_editor set --login-path=local --host=localhost --user=assistant --password
mysql_config_editor print --all
mysql --login-path=local --database=assistant < database.sql