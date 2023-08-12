#!/bin/bash

set -e

# disable default site
a2dissite 000-default

# install sites
cp /vagrant/provisioning/etc/apache2/sites-available/xbmc-video-server.conf /etc/apache2/sites-available
cp /vagrant/provisioning/etc/apache2/sites-available/xbmc-video-server-ssl.conf /etc/apache2/sites-available
a2ensite xbmc-video-server
a2ensite xbmc-video-server-ssl

# configure xdebug
cp /vagrant/provisioning/etc/php/8.1/mods-available/xdebug.ini /etc/php/8.1/mods-available/xdebug.ini

# enable modules and restart
a2enmod rewrite expires rewrite ssl
service apache2 restart
