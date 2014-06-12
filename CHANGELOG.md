Changelog
=================

This file tracks the changes to the application over time. If for some reason 
you need to use an older version than the current one you can do so by checking 
out that specific tag (e.g. `git checkout 1.4.3`). To later switch to the latest 
version, run `git checkout master && git pull`. Beware that switching to a 
previous version from a newer one may break something since the project dependencies 
change over time.

#### 1.5.1
* added missing column to the schema files (fixes #150)
* fixed some JavaScript errors on the initial backend configuration page
* added ability to change language and log out while the application is not yet fully configured
* moved the backend connectivity check to MediaController (fixes #149, #146)
* other minor fixes and improvements
