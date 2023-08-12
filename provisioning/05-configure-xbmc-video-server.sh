#!/bin/bash

set -e

{
    cd /vagrant

    #install dependencies
    wget https://github.com/composer/composer/releases/download/2.5.8/composer.phar -O composer.phar
    php composer.phar install

    # configure application
    php ./src/protected/yiic.php createinitialdatabase
} > /dev/null 2>&1
