[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jalle19/xbmc-video-server/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/xbmc-video-server/?branch=master)

XBMC Video Server
=================

This is a standalone web interface for XBMC which allows users to browse the library and download or stream the movies and TV shows in it.

Features
--------

* Browse and filter movies and TV shows
* Browse seasons and episodes for a TV show
* Stream media using an M3U playlist with one click
* User management (application requires login), including logging to see who's doing what and restricting access based on a whitelist
* Supports multiple XBMC instances and allows easy switching between them, including the ability to wake them using WOL if it's not available
* No configuration files
* Multiple languages
* Customizable interface

Screenshots
-----------

[![screenshot1](http://t.imgbox.com/E9TRJ1gS.jpg)](http://i.imgbox.com/E9TRJ1gS.jpg) [![screenshot2](http://t.imgbox.com/d8VVi46K.jpg)](http://i.imgbox.com/d8VVi46K.jpg) 
[![screenshot3](http://t.imgbox.com/actvy5rQ.jpg)](http://i.imgbox.com/actvy5rQ.jpg) 
[![screenshot4](http://t.imgbox.com/acwlf35k.jpg)](http://i.imgbox.com/acwlf35k.jpg)  [![screenshot5](http://t.imgbox.com/PDzqmqG4.jpg)](http://i.imgbox.com/PDzqmqG4.jpg) 
[![screenshot6](http://t.imgbox.com/UxvIw9vB.jpg)](http://i.imgbox.com/UxvIw9vB.jpg) 
[![screenshot7](http://t.imgbox.com/acumBdVg.jpg)](http://i.imgbox.com/acumBdVg.jpg) 
[![screenshot8](http://t.imgbox.com/acoIuF8V.jpg)](http://i.imgbox.com/acoIuF8V.jpg)

Requirements
------------

* PHP 5.4
* allow_url_fopen = On in php.ini
* Apache with .htaccess support enabled
* XBMC 12 "Frodo" (seeking works reliably on v13 "Gotham" only)

Installation and usage
----------------------

The project's [wiki pages](https://github.com/Jalle19/xbmc-video-server/wiki) contain everything you need to know about installing, configuring and using this application.

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

mobiledetectlib (https://github.com/serbanghita/Mobile-Detect) and gavroche/browser (https://github.com/gabrielbull/php-browser)

jsonmapper (https://github.com/netresearch/jsonmapper)

Bootswatch (http://bootswatch.com/)

Font Awesome (http://fortawesome.github.io/Font-Awesome/)

jQuery Unveil (http://luis-almeida.github.io/unveil/)

typeahead.js (http://twitter.github.io/typeahead.js/)


License
-------

This software is licensed under the GNU GENERAL PUBLIC LICENSE Version 3.
