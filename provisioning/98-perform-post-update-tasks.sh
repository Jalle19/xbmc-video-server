#!/bin/bash

cd /vagrant

# update dependencies and perform any pending migrations
php composer.phar install > /dev/null 2>&1
php ./src/protected/yiic.php migrate --interactive=0 > /dev/null 2>&1
