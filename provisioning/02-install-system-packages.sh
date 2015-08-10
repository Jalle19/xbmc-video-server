#!/bin/bash

# install dependencies
apt-get -qq update
apt-get -y upgrade > /dev/null 2>&1
apt-get -y install libapache2-mod-php5 php5-imagick php5-cli php5-sqlite php5-json curl nodejs nodejs-legacy npm > /dev/null 2>&1
