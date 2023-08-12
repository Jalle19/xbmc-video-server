#!/bin/bash

set -e

# Workaround for https://bugs.launchpad.net/ubuntu/+source/procps/+bug/50093
sudo sysctl --system > /dev/null 2>&1

IP_ADDR=`ifconfig | grep "inet " | grep -v "127.0.0.1\|10.0.2." | awk '{print $2}' | cut -d ":" -f2`

echo "XBMC Video Server is now available at: http://$IP_ADDR/"
