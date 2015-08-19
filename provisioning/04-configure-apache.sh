#!/bin/bash

# disable default site
a2dissite 000-default > /dev/null 2>&1

# install site
cp /vagrant/provisioning/etc/apache2/sites-available/xbmc-video-server.conf /etc/apache2/sites-available
a2ensite xbmc-video-server > /dev/null 2>&1

# enable modules and restart
a2enmod rewrite expires rewrite > /dev/null 2>&1
service apache2 restart > /dev/null 2>&1
