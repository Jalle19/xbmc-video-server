#!/bin/bash

{
    cd /vagrant

    #install dependencies
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install > /dev/null 2>&1

    # configure application
    php ./src/protected/yiic.php createinitialdatabase
} > /dev/null 2>&1
