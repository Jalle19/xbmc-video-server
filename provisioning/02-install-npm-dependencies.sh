#!/bin/bash

cd /vagrant

# --no-bin-links is required on Windows
npm install --no-bin-links > /dev/null 2>&1
