Changelog
=================

This file tracks the changes to the application over time. If for some reason 
you need to use an older version than the current one you can do so by checking 
out that specific tag (e.g. `git checkout 1.4.3`). To later switch to the latest 
version, run `git checkout master && git pull`. Beware that switching to a 
previous version from a newer one may break something since the project dependencies 
change over time.

#### 1.6.0
* upgrade the icons to use Font Awesome 4.1, and drop Fontastic
* fix the viewport scaling on mobile devices, now it should switch to showing 
thumbnails in grid view mode when the phone is tilted
* use the same default display mode regardless of device now that grid view 
works better on phones
* indicate watched status for movies, TV shows, seasons and episodes
* persist the user's selected display mode so it doesn't change from session to 
session or browser to browser

#### 1.5.4
* use Grunt to concatenate and minify scripts
* scroll to the top of the page when moving between result pages
* don't save the subnet mask for a backend if no MAC address has been specified
* a bunch of code improvements

#### 1.5.3
* fixed applying all migrations on a fresh database
* use Grunt instead of yii-less to compile LESS files, with the added bonus that the minification can be done automatically too

#### 1.5.2
* updated French translation

#### 1.5.1
* added missing column to the schema files (fixes #150)
* fixed some JavaScript errors on the initial backend configuration page
* added ability to change language and log out while the application is not yet fully configured
* moved the backend connectivity check to MediaController (fixes #149, #146)
* other minor fixes and improvements
