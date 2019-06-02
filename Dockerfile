FROM php:7.1-apache
LABEL maintainer="Sam Stenvall <neggelandia@gmail.com>, Richard Weber <riche.weber@gmail.com>, Tomas Strand <tomas@fik1.net>"

ENV ALLOW_OVERRIDE=true
ENV APACHE_DOCUMENT_ROOT /app
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#Git is good for composer
RUN apt-get update && \
    apt-get install -yq git mercurial unzip imagemagick magickwand-6.q16-dev && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite expires
RUN pecl install imagick && docker-php-ext-enable imagick

RUN rm -fr /app

ADD . /src

WORKDIR /src
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --no-plugins --no-scripts
RUN php ./src/protected/yiic createinitialdatabase
RUN php ./src/protected/yiic setpermissions
RUN php ./src/protected/yiic.php migrate --interactive=0

RUN ln -s /src/src/ /app

VOLUME /src/src/protected/data
VOLUME /src/src/protected/runtime
VOLUME /src/src/images
