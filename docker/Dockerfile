FROM ubuntu:jammy
MAINTAINER Sam Stenvall <neggelandia@gmail.com>

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -yq \
    apache2 libapache2-mod-php php-imagick php-cli php-sqlite3 php-json php-mysql php-mbstring git curl unzip composer && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite expires headers proxy_http

RUN mkdir -p /app /shared
ADD . /app
WORKDIR /app

RUN composer install --optimize-autoloader --classmap-authoritative
	
RUN rm -rf /app/src/images/image-cache && \
	ln -sf /shared/image-cache /app/src/images/image-cache && \
	rm -f /app/src/protected/data/xbmc-video-server.db && \
	ln -sf /shared/database/xbmc-video-server.db /app/src/protected/data/xbmc-video-server.db && \
	rm -rf /app/src/protected/runtime && \
	ln -sf /shared/runtime /app/src/protected/runtime && \
    rm -f /etc/apache2/sites-enabled/* && \
	ln -sf /shared/config/apache2/xbmc-video-server.conf /etc/apache2/sites-enabled/xbmc-video-server.conf
	
VOLUME /shared

EXPOSE 80

ADD docker/shared /shared.template
ADD docker/docker-entrypoint.sh /docker-entrypoint.sh

ENTRYPOINT ["/bin/bash", "/docker-entrypoint.sh"]
