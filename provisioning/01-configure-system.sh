#!/bin/bash

{
    # disable IPv6 (doesn't work if the host runs Windows and is on wireless)
    echo "net.ipv6.conf.all.disable_ipv6 = 1" > /etc/sysctl.d/60-disable-ipv6.conf
    service procps start

    # enable swap (disable first to prevent errors during re-provisioning)
    swapoff /tmp/swap
    dd if=/dev/zero of=/tmp/swap bs=1M count=512
    mkswap /tmp/swap
    swapon /tmp/swap
} > /dev/null 2>&1
