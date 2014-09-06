Changelog
=================

This file tracks the changes to the application over time. If for some reason 
you need to use an older version than the current one you can do so by checking 
out that specific tag (e.g. `git checkout 1.4.3`). To later switch to the latest 
version, run `git checkout master && git pull`. Beware that switching to a 
previous version from a newer one may break something since the project dependencies 
change over time.

#### 1.6.7
* show watch/download options in a modal dialog instead
* added ability to select the playlist format used for playlists (the application default is selected by default)
* added ability to start playing an item in XBMC instead of streaming/downloading it

#### 1.6.6
* fix adding and using backends using an IPv6 address
* fixed a bug where normal users couldn't wake a sleeping backend using WOL
* add typeahead functionality for the actor filter field
* add ability to filter TV shows by actor too
* display movie director on the details page
* add ability to filter movies by director

#### 1.6.5
* added a missing translation
* moved installation instructions from the README to the wiki on Github
* fix a regression that made lists unsortable
* fix a bug where spectators could see and use the "Watch the whole show" button

#### 1.6.4
* fixed a copy-paste error when logging streamed TV shows
* under-the-hood improvements to the way "not found" pages are generated

#### 1.6.3
* added German translation (thanks to @Victor61)
* added a missing French translation
* increased the backend socket timeout to 5 seconds, this should hopefully fix some "The backend is not connectable" error messages
* added ability to retrieve a playlist for a complete season

#### 1.6.2
* fixed the page title on the Login page
* added missing French translation on the Settings page
* added option to customize or remove the application subtitle

#### 1.6.1
* add support for PLS and XSPF playlists. The playlist format can be changed from the settings. XSPF playlists support item images, so if you open a movie playlist in VLC you'll see the movie poster
* lots of various code improvements

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
