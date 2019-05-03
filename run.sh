#!/bin/bash
php composer.phar dumpautoload
./vendor/bin/phpunit --bootstrap ./vendor/autoload.php tests