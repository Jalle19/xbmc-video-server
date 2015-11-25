#!/bin/bash

{
    # disable default site
    a2dissite 000-default
    rm /etc/nginx/sites-enabled/default

    # install sites
    cp /vagrant/provisioning/etc/apache2/sites-available/xbmc-video-server.conf /etc/apache2/sites-available
    cp /vagrant/provisioning/etc/apache2/sites-available/xbmc-video-server-ssl.conf /etc/apache2/sites-available
    cp /vagrant/provisioning/etc/nginx/sites-available/xbmc-video-server /etc/nginx/sites-available
    a2ensite xbmc-video-server
    a2ensite xbmc-video-server-ssl
    ln -s /etc/nginx/sites-available/xbmc-video-server /etc/nginx/sites-enabled/xbmc-video-server

    # enable modules and restart
    a2enmod rewrite expires rewrite ssl
    service apache2 restart
    service nginx restart
} > /dev/null 2>&1
