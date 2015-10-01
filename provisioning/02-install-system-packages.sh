#!/bin/bash

{
    # install dependencies
    apt-get update
    apt-get -y upgrade
    apt-get -y install libapache2-mod-php5 php5-imagick php5-cli php5-sqlite \
                       php5-json php5-xdebug curl nodejs nodejs-legacy npm
} > /dev/null 2>&1
