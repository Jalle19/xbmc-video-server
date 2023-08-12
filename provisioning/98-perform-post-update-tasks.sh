#!/bin/bash

set -e

cd /vagrant

# update dependencies and perform any pending migrations
php composer.phar install
php ./src/protected/yiic.php migrate --interactive=0
