#!/bin/bash

{
    # install dependencies
    apt-get update
    apt-get -y upgrade
    apt-get -y install libapache2-mod-php5 php5-imagick php5-cli php5-sqlite \
                       php5-json php5-xdebug curl nodejs nodejs-legacy npm \
                       nginx php5-fpm

    # disable xdebug for php5-cli, Composer doesn't like it and we don't really 
    # need it anyway
    rm /etc/php5/cli/conf.d/20-xdebug.ini
} > /dev/null 2>&1
