#!/bin/bash

cd /vagrant

# grunt-cli needs to be installed globally
npm install -g grunt-cli > /dev/null 2>&1

# --no-bin-links is required on Windows
npm install --no-bin-links > /dev/null 2>&1
