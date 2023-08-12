[![Build Status](https://travis-ci.org/Jalle19/xbmc-video-server.svg?branch=master)](https://travis-ci.org/Jalle19/xbmc-video-server)
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
* Supports multiple XBMC instances and allows easy switching between them, including the ability to suspend instances and wake them using WOL
* No configuration files
* Multiple languages
* Customizable interface

Screenshots
-----------

[![screenshot1](http://8.t.imgbox.com/nBiLdhfD.jpg)](http://i.imgbox.com/nBiLdhfD.png) 
[![screenshot2](http://9.t.imgbox.com/5mvkZ94f.jpg)](http://i.imgbox.com/5mvkZ94f.png) 
[![screenshot3](http://1.t.imgbox.com/0WsVzmzT.jpg)](http://i.imgbox.com/0WsVzmzT.png) 
[![screenshot4](http://6.t.imgbox.com/XaIUObRu.jpg)](http://i.imgbox.com/XaIUObRu.png) 
[![screenshot5](http://4.t.imgbox.com/9dI7zzJk.jpg)](http://i.imgbox.com/9dI7zzJk.png) 
[![screenshot6](http://5.t.imgbox.com/H97dUCsL.jpg)](http://i.imgbox.com/H97dUCsL.png) 
[![screenshot7](http://8.t.imgbox.com/NyEP3MDa.jpg)](http://i.imgbox.com/NyEP3MDa.png) 
[![screenshot8](http://8.t.imgbox.com/yRTNvPbS.jpg)](http://i.imgbox.com/yRTNvPbS.png)

Requirements
------------

* PHP >= 5.5
* allow_url_fopen = On in php.ini
* Apache with .htaccess support enabled
* XBMC 12 "Frodo" or newer

Installation and usage
----------------------

The project's [wiki pages](https://github.com/Jalle19/xbmc-video-server/wiki) contain everything you need to know about installing, configuring and using this application.

Credits
-------

* XBMC (http://xbmc.org/)
* Yii framework (http://www.yiiframework.com/)
* Yiistrap (http://www.getyiistrap.com/)
* Yii-less (https://github.com/Crisu83/yii-less)
* yii-consoletools (https://github.com/Crisu83/yii-consoletools)
* Imagine (https://github.com/avalanche123/Imagine)
* phpass (http://www.openwall.com/phpass/) (https://github.com/hautelook/phpass)
* Zend framework (http://framework.zend.com/)
* mobiledetectlib (https://github.com/serbanghita/Mobile-Detect) and gavroche/browser (https://github.com/gabrielbull/php-browser)
* jsonmapper (https://github.com/netresearch/jsonmapper)
* Bootswatch (http://bootswatch.com/)
* Font Awesome (http://fortawesome.github.io/Font-Awesome/)
* jQuery Unveil (http://luis-almeida.github.io/unveil/)
* typeahead.js (http://twitter.github.io/typeahead.js/)
* phpwol (https://github.com/TomNomNom/phpwol)

License
-------

This software is licensed under the GNU GENERAL PUBLIC LICENSE Version 3.

The bundled font is Copyright (c) 2010-2014 by tyPoland Lukasz Dziedzic (team@latofonts.com) with Reserved Font Name 
"Lato"
