#!/bin/bash
echo 'Installing PHP packages'
sudo apt-get install php-xml php-json php-mbstring php-mysql
echo -n

echo 'Installing local Composer'
curl -sS https://getcomposer.org/installer | php
php composer.phar install
echo -n

echo 'Installing PHP package for Apache 2'
sudo apt install libapache2-mod-php
sudo ln -s `pwd` /var/www/html/HomeAssistant
echo -n

echo 'Seting up home_test MySQL database'
echo 'Type password: home_test'
mysql_config_editor set --login-path=local --host=localhost --user=home_test --password
mysql_config_editor print --all
mysql --login-path=local --database=home_test < src/CarMaintenance/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/CatFeeding/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Coffee/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Flat/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Shopping/Infrastructure/database.sql
