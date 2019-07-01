#!/bin/bash

{
    # install dependencies
    apt-get update
    DEBIAN_FRONTEND=noninteractive apt-get -y upgrade
    DEBIAN_FRONTEND=noninteractive apt-get -y install libapache2-mod-php php-imagick php-cli php-sqlite3 \
                       php-json php-xdebug curl nodejs npm unzip
} > /dev/null 2>&1
