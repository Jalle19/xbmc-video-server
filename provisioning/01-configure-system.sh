#!/bin/bash

{
    # enable swap (disable first to prevent errors during re-provisioning)
    swapoff /tmp/swap
    dd if=/dev/zero of=/tmp/swap bs=1M count=512
    mkswap /tmp/swap
    swapon /tmp/swap
} > /dev/null 2>&1
