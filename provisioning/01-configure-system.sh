#!/bin/bash

set -e

{
    # disable IPv6 (doesn't work if the host is on wireless)
    echo "net.ipv6.conf.all.disable_ipv6 = 1" > /etc/sysctl.d/60-disable-ipv6.conf
    echo "net.ipv6.conf.default.disable_ipv6 = 1" >> /etc/sysctl.d/60-disable-ipv6.conf
    sudo sysctl --system

    # enable swap (disable first to prevent errors during re-provisioning)
    swapoff /tmp/swap || true
    dd if=/dev/zero of=/tmp/swap bs=1M count=1024
    mkswap /tmp/swap
    chmod 600 /tmp/swap
    swapon /tmp/swap
} > /dev/null 2>&1
