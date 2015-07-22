Changelog
=================

This file tracks the changes to the application over time. If for some reason 
you need to use an older version than the current one you can do so by checking 
out that specific tag (e.g. `git checkout 1.4.3`). To later switch to the latest 
version, run `git checkout master && git pull`. Beware that switching to a 
previous version from a newer one may break something since the project dependencies 
change over time.

#### 1.6.15
* increased memory limit and execution time to avoid timeouts on large libraries combined with slow devices
* updated README screenshots
* hopefully fixed incompatibility with Kodi Isengard backends running on Windows
* honor the request timeout for image retrievals as well

#### 1.6.14
* updated dependencies

#### 1.6.13
* fixed broken database schema files which lead to HTTP 400 errors on new installations

#### 1.6.12
* some minor changes to the style sheet to improve the grid layout on all resolutions
* minor updates to the French translation (thanks to @Galixte)
* changed to use case-insensitive matching for usernames during login
* divided the settings area into two sections for improved readability
* fixed library update functionality on certain Isengard alpha backends
* added a new setting to control the request timeout. The default has also been increased from 10 to 30 seconds.
* minor change to the installation instructions (on the wiki) to workaround an issue where yiic may be unexecutable out of the box

#### 1.6.11
* updated German translation (thanks to @Victor61)
* added screen width override for 1366px wide screens
* made the "Play in XBMC" button a link below the "Download" links instead
* made the "Watch in browser" button the same color as the old "Play in XBMC" button
* don't show the "Watch as playlist" button for files that cannot be streamed (raw DVDs and Blurays)

#### 1.6.10
* include the address when logging login attempts
* update icons in the watch modal dialog
* enable the "Watch in browser" button for H.264/AAC/MKV files too since they work in Chrome
* added missing logging when the "Watch in browser" button is clicked

#### 1.6.9
* show a friendlier error message when "Update library" is clicked and the backend is unconnectable
* log the full request body when an API call produces an error. This makes debugging easier.
* add ability to play compatible files directly in the browser
* performance improvements, especially on the movie browse page
* fixed incorrect URL in the backend form

#### 1.6.8
* enable database schema caching by default. It is cleared automatically when applying migrations that change it.
* load Google Fonts over HTTPS if the site itself is accessed over HTTPS (fixes mixed content warnings in the browser)
* added missing line breaks when formatting logged exceptions
* log error data when a JSON-RPC error occurs
* made the "application name" setting optional. This means nothing is rendered above the main menu if both application name and subtitle are left empty.
* the "Cast" section is no longer rendered if there is no cast information available (happens on foreign TV shows and obscure movies)
* the actor grid is now rendered the same for both movies and TV shows, i.e. clicking an actor thumbnail on a TV show page will show what movies said actor is in.
* make the layout adapt better to larger screens. Now the page gets wider and wider (up to 1920x1080) instead of staying fixed at 1170 pixels maximum.

#### 1.6.7.2
* added missing French translations

#### 1.6.7.1
* only allow administrators to play media on the backend

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
