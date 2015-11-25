FROM tutum/apache-php
MAINTAINER Sam Stenvall <neggelandia@gmail.com>, Richard Weber <riche.weber@gmail.com>

ENV ALLOW_OVERRIDE=true
#Git is good for composer
RUN apt-get update && \
    apt-get install -yq git php5-imagick php5-sqlite php5-json && \
    rm -rf /var/lib/apt/lists/*
RUN a2enmod rewrite expires

RUN rm -fr /app

ADD . /src

WORKDIR /src
RUN composer install
RUN ./src/protected/yiic createinitialdatabase
RUN ./src/protected/yiic setpermissions
RUN php ./src/protected/yiic.php migrate --interactive=0

RUN ln -s /src/src/ /app

VOLUME /src/src/protected/data
VOLUME /src/src/protected/runtime
VOLUME /src/src/images
