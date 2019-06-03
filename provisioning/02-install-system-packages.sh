#!/bin/bash

{
    # install dependencies
    apt-get update
    apt-get -y upgrade
    apt-get -y install libapache2-mod-php php-imagick php-cli php-sqlite3 \
                       php-json php-xdebug curl nodejs npm unzip
} > /dev/null 2>&1
