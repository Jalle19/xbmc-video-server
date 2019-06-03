FROM php:7.1-apache
LABEL maintainer="Sam Stenvall <neggelandia@gmail.com>, Richard Weber <riche.weber@gmail.com>, Tomas Strand <tomas@fik1.net>"

ENV ALLOW_OVERRIDE=true

#Git is good for composer
RUN apt-get update && \
    apt-get install -yq \
    git \
    imagemagick \
    magickwand-6.q16-dev \
    unzip \
  && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite expires
RUN pecl install imagick && docker-php-ext-enable imagick

ADD . /var/www/html

WORKDIR /var/www/html
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --no-plugins --no-scripts
RUN php ./src/protected/yiic createinitialdatabase
RUN php ./src/protected/yiic setpermissions
RUN php ./src/protected/yiic.php migrate --interactive=0

VOLUME /var/www/html/src/protected/data
VOLUME /var/www/html/src/protected/runtime
VOLUME /var/www/html/src/images
