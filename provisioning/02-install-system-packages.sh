#!/bin/bash

set -e

{
    # configure debconf for mysql-server
    echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
    echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections

    # install dependencies
    apt-get update
    DEBIAN_FRONTEND=noninteractive apt-get -y upgrade
    DEBIAN_FRONTEND=noninteractive apt-get -y install libapache2-mod-php php-imagick php-cli php-sqlite3 \
                       php-json php-mbstring php-xdebug curl nodejs npm unzip mysql-server php-mysql
} > /dev/null 2>&1
