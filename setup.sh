#!/bin/bash
TITLE_END='\n-------------------------------------\n'
TITLE_START="\n\n$TITLE_END"
echo -e "${TITLE_START}Installing PHP packages${TITLE_END}"
sudo apt install php-xml php-json php-mbstring php-mysql

echo -e "${TITLE_START}Installing local Composer${TITLE_END}"
curl -sS https://getcomposer.org/installer | php
php composer.phar install

echo -e "${TITLE_START}Installing PHP package for Apache 2${TITLE_END}"
sudo apt install libapache2-mod-php

echo -e "${TITLE_START}Install web page${TITLE_END}"
APP_DIR=/var/www/html/Home
if test -d $APP_DIR;
then
	echo 'Already installed'
else
	sudo ln -s `pwd` $APP_DIR
fi

echo -e "${TITLE_START}Seting up MySQL databases${TITLE_END}"
sudo mysql < src/Shared/Infrastructure/database.sql
echo 'Type password: home'
mysql_config_editor set --login-path=local --host=localhost --user=home --password
mysql_config_editor print --all
mysql --login-path=local --database=home_test < src/CarMaintenance/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/CatFeeding/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Coffee/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Flat/Infrastructure/database.sql
mysql --login-path=local --database=home_test < src/Shopping/Infrastructure/database.sql

mysql --login-path=local --database=home < src/CarMaintenance/Infrastructure/database.sql
mysql --login-path=local --database=home < src/CatFeeding/Infrastructure/database.sql
mysql --login-path=local --database=home < src/Coffee/Infrastructure/database.sql
mysql --login-path=local --database=home < src/Flat/Infrastructure/database.sql
mysql --login-path=local --database=home < src/Shopping/Infrastructure/database.sql
