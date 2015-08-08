#!/bin/bash

# disable IPv6 (doesn't work if the host runs Windows and is on wireless)
echo "net.ipv6.conf.all.disable_ipv6 = 1" > /etc/sysctl.d/60-disable-ipv6.conf
service procps start > /dev/null 2>&1

# install dependencies
apt-get -qq update
apt-get -y upgrade > /dev/null 2>&1
apt-get -y install libapache2-mod-php5 php5-gd php5-cli php5-sqlite php5-json curl nodejs nodejs-legacy npm > /dev/null 2>&1
