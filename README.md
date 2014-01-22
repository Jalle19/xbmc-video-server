XBMC Video Server
=================

This is a standalone web interface for XBMC which allows users to browse the library and download or stream the movies and TV shows in it.

Features
--------

* Browse and filter movies and TV shows
* Browse seasons and episodes for a TV show
* Stream media using an M3U playlist with one click
* User management (application requires login), including logging to see who's doing what
* Supports multiple XBMC instances and allows easy switching between them
* No configuration files

Screenshots
-----------

[![screenshot1](http://t.imgbox.com/aciCBLuI.jpg)](http://i.imgbox.com/aciCBLuI.jpg) [![screenshot2](http://t.imgbox.com/actvy5rQ.jpg)](http://i.imgbox.com/actvy5rQ.jpg) 
[![screenshot3](http://t.imgbox.com/acwlf35k.jpg)](http://i.imgbox.com/acwlf35k.jpg) [![screenshot4](http://t.imgbox.com/addMRcXA.jpg)](http://i.imgbox.com/addMRcXA.jpg) 
[![screenshot5](http://t.imgbox.com/acoIuF8V.jpg)](http://i.imgbox.com/acoIuF8V.jpg) [![screenshot6](http://t.imgbox.com/acumBdVg.jpg)](http://i.imgbox.com/acumBdVg.jpg)

Requirements
------------

* PHP 5.4
* allow_url_fopen = On in php.ini
* Apache with .htaccess support enabled
* XBMC 12 "Frodo" (seeking works reliably on v13 "Gotham" only)

Installation
------------

### Linux

The following steps assume that your shell user is able to use the `sudo` command, that you're running a Debian/Ubuntu-based operating system, that you're going to use Apache as web server and that its document root is located in /var/www.

Run the following commands, one by one, in the exact order shown here:

```
sudo su 
apt-get install libapache2-mod-php5 php5-gd php5-cli php5-sqlite php5-json git curl
a2enmod rewrite expires
service apache2 restart
cd /var/www
git clone git://github.com/Jalle19/xbmc-video-server.git
cd xbmc-video-server
curl -sS https://getcomposer.org/installer | php
php composer.phar install
./src/protected/yiic createinitialdatabase
./src/protected/yiic setpermissions
```

After running the commands above you'll have to add a few lines to Apache's default site configuration to allow `.htaccess` files to be used. The exact process varies depending on which version of Linux you use.

#### Debian and Ubuntu 13.04 or older

Open the file `/etc/apache2/sites-available/default` and add the following right before the last line (`</VirtualHost>`):

```
<Directory /var/www/xbmc-video-server/>
	Options FollowSymLinks
	AllowOverride All
	Order allow,deny
	allow from all
</Directory>
```

After saving the file you must restart Apache using `service apache2 restart` for the changes to take effect.

#### Ubuntu 13.10 and newer

Open the file `/etc/apache2/sites-available/000-default` and add the following right before the last line (`</VirtualHost>`):

```
<Directory /var/www/xbmc-video-server/>
	Options FollowSymLinks
	AllowOverride All
	Require all granted
</Directory>
```

After saving the file you must restart Apache using `service apache2 restart` for the changes to take effect.

In addition to the above steps you'll also need to edit `/etc/php5/apache2/php.ini` and change the line `;date.timezone =` to something like this: `date.timezone = Europe/Helsinki`. Look at http://us2.php.net/manual/en/datetime.configuration.php#113068 for possible timezone values.

After saving the file you must restart Apache using `service apache2 restart` for the changes to take effect.

#### Updating on Linux

To update your copy of the software to the latest version, run the following commands (assuming the same directory structure and setup as described under Installation):

```
cd /var/www/xbmc-video-server
git pull
php composer.phar install
./src/protected/yiic migrate --interactive=0
```

### Windows

1. Download the XAMPP installer from http://sourceforge.net/projects/xampp/ and run it
	* Uncheck all components except *Server / Apache* and *Program Languages / PHP*
	* Follow the instructions until the installation is complete (ignore any warnings). Don't open the XAMPP control panel just yet.
2. Open the file `C:\xampp\php\php.ini` and remove the leading semi-colon from the line reading `;extension=php_openssl.dll`. Save the file.
3. Download the Composer installer from http://getcomposer.org/download/ and run it
	* Browse to `C:\xampp\php\php.exe` when the installer asks for the path to php.exe
4. Download the msysgit installer from http://code.google.com/p/msysgit/ and run it
	* Use the default options on all screens except at *Adjusting your PATH environment*, there you must select *Run Git from the Windows Command Prompt*.
5. Start the XAMPP Control Panel
	* Click the *Config* button and check Apache under *Autostart of modules*, then click *Save*
	* Start Apache by clicking the *Start* button in the middle of the window
6. While still in the XAMPP Control Panel, click *Shell* to launch a command prompt in the XAMPP directory. Then, run the following commands, one by one:

```
cd htdocs
git clone git://github.com/Jalle19/xbmc-video-server.git
cd xbmc-video-server
composer install
.\src\protected\yiic createinitialdatabase
```

#### Updating on Windows

Open the XAMPP Control Panel and click the Shell button to launch a command prompt in the XAMPP directory. Then, run the following commands one by one:


```
cd htdocs\xbmc-video-server
git pull
composer install
.\src\protected\yiic migrate --interactive=0
```

### Mac OS X

1. Download the XAMPP installer for Mac OS X from http://sourceforge.net/projects/xampp/ and run it. Follow the instructions until the setup is complete. Once completed it will automatically run Apache and open the web server test page.

2. Open `/Applications/XAMPP/etc/php.ini` and remove the leading semi-colon from the line reading `;extension=php_openssl.dll`, then save the file.

3. Run `/Applications/XAMPP/manager-osx.app`, go to the Manage Servers tab, select Apache and click Restart.

4. Download and install Git from http://git-scm.com/downloads/. You may have to Ctrl-right-click (hold the Ctrl key while right-clicking) the file and select Open in order to be able to install it.

5. Open a terminal and run the following commands one by one:

```
cd /Applications/XAMPP/htdocs
git clone git://github.com/Jalle19/xbmc-video-server.git
cd xbmc-video-server
curl -sS https://getcomposer.org/installer | php -d detect_unicode=Off
php -d detect_unicode=Off composer.phar install
./src/protected/yiic createinitialdatabase
./src/protected/yiic setpermissions
```

#### Updating on Mac OS X

Open a terminal and run the following commands:

```
cd /Applications/XAMPP/htdocs/xbmc-video-server
git pull
php -d detect_unicode=Off composer.phar install
./src/protected/yiic migrate --interactive=0
```

Initial setup
-------------

Once the installation is complete, use your web browser to browse to /xbmc-video-server on your web server (if you did the installation steps on your local machine the URL would be http://localhost/xbmc-video-server). You will be presented with a login form. Log in with username "admin" and password "admin" (you'll be able to change this later).

Once logged in, you will be asked to configure a backend. A backend is an instance of XBMC that the application connects to and displays library contents from. Here you should specify XBMC's hostname and port as well as the username and password for XBMC's web server.

If you specify more than one backend, a "Change backend" menu item will appear which allows you to switch which backend is being used. This way you don't have to install this application once for every XBMC instance you have, or you can use it to connect to a friend's library (provided he has opened the relevant ports in his firewall).

Proxy Location
--------------

The "Proxy Location" setting is a bit more exotic. Without it, all requests to the XBMC API (including the URLs to your movies and TV shows) are in the form of http://user:pass@hostname:port/vfs/pathtomovie.mkv, which means in order to use the application over the Internet you'd have to forward the right port to the machine running XBMC. It also means you'd be exposing the XBMC API credentials to anyone using your application.

To avoid this we can tell Apache to forward requests on `/xbmc-vfs` to `http://user:pass@hostname:port/vfs`, this way your API credentials won't leak through the media URLs.

### Example

Let's say you have installed XBMC Video Server on one machine, and XBMC is running on a different machine:

http://xbmc-video-server.example.com/xbmc-video-server/

http://xbmc.example.com:8080/

What we want to do is map e.g. `/xbmc-vfs` to `http://xbmc.example.com:8080/vfs`, this way you don't have to forward the 8080 port to the computer running XBMC and we avoid exposing the XBMC API to the Internet.

### Configuring a reverse proxy

To make this work you have to configure your web server to provide a reverse proxy. To do this, open the file `/etc/apache2/sites-available/default` (`/etc/apache2/sites-available/000-default.conf` on newer Ubuntu versions) and add the following inside the `<VirtualHost *:80>` block:

```
	AllowEncodedSlashes On
	
	<Location /xbmc-vfs>
		ProxyPass http://xbmc.example.com:8080/vfs
		ProxyPassReverse http://xbmc.example.com:8080/vfs
		RequestHeader set Authorization "Basic eGJtYzp4Ym1j"
	</Location>
```

After saving the file also have to run `sudo a2enmod headers proxy_http && sudo service apache2 restart`.

In the example above the "Proxy Location" field in the XBMC Video Server settings should contain `/xbmc-vfs`. The "eGJtYzp4Ym1j" part is username:password encoded with Base64 (in the example the credentials are the default xbmc:xbmc).

**You should use a randomly generated long string as location (see Security implications)**

User management
---------------

Once you've configured the application you should be able to browse your library. You can configure new users from the Settings menu. There are three user roles; user, administrator and spectator. A standard user cannot see (and cannot access by other means) the settings pages, a spectator can't stream or download anything (just "spectate"), and an administrator can naturally do everything.

Security implications
---------------------

This application is insecure by virtue of design. Since there is only one set of credentials for XBMCs API and the only way to authenticate from a media player (such as VLC) is by passing the credentials in the URL. You can avoid exposing the actual API and API credentials to your users by configuring a proxy location, but that exposes the XBMC virtual filesystem on an authentication-less URL. Thus, if you use a proxy location, you should specify a non-guessable one so that outsiders can't accidentally gain access to your media files.

Regardless of whether you use a reverse proxy or not, your smb:// login credentials will be visible in the URLs generated by the application. If this is a concern you should consider mounting the network share in your OS instead of via XBMC.

Developers
----------

The application comes with pre-compiled CSS files. If you wish to re-compile the files on the fly you need to preload the "less" component and optionally change the path to node and lessc. All of this is done in `src/protected/config/main.php`.

Credits
-------

XBMC (http://xbmc.org/)

Yii framework (http://www.yiiframework.com/)

Yiistrap (http://www.getyiistrap.com/)

Yii-less (https://github.com/Crisu83/yii-less)

yii-consoletools (https://github.com/Crisu83/yii-consoletools)

eventviva/php-image-resize (https://github.com/eventviva/php-image-resize)

phpass (http://www.openwall.com/phpass/) (https://github.com/hautelook/phpass)

Zend framework (http://framework.zend.com/)

Bootswatch (http://bootswatch.com/)

Font Awesome (http://fortawesome.github.io/Font-Awesome/)

jQuery Unveil (http://luis-almeida.github.io/unveil/)

typeahead.js (http://twitter.github.io/typeahead.js/)


License
-------

This software is licensed under the GNU GENERAL PUBLIC LICENSE Version 3.
