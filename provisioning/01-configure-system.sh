#!/bin/bash

# disable IPv6 (doesn't work if the host runs Windows and is on wireless)
echo "net.ipv6.conf.all.disable_ipv6 = 1" > /etc/sysctl.d/60-disable-ipv6.conf
service procps start > /dev/null 2>&1

# enable swap
dd if=/dev/zero of=/tmp/swap bs=1M count=512 > /dev/null 2>&1
mkswap /tmp/swap > /dev/null 2>&1
swapon /tmp/swap > /dev/null 2>&1
