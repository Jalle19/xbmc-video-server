# Contributing

This document details what you can do to contribute to this project.

## Code contributions

Code is always welcome. To contribute a patch or a feature, fork the project and when you're done, send a [pull request](https://help.github.com/articles/creating-a-pull-request). See the Github docs on how to do it if you're unsure.

If you're adding new .php files to the project please remember to add the copyright header (see other files for example) and adjust the `@author` comment if necessary. If you're adding modifying an existing .php file and feel like it's an important change then add another `@author` line in the class documentation.

## Translations

Translations are always welcome, and even though it involves a little bit of work (apart from the actual translating) it's not that complicated. Here's what you need to do:

1. Fork the project on Github and check out a local copy to your computer. A good tool for this is [SourceTree](http://www.sourcetreeapp.com/).
2. Open the file `src/protected/config/message.php` and add the language you want to translate into to the `languages` array.
3. In a terminal, run the command `src/protected/yiic message src/protected/config/message.php`. This will create a new set of files in the `src/protected/messages/<language>` directory.
4. Fill in the blanks in all the generated files and create a [pull request](https://help.github.com/articles/creating-a-pull-request).

If you need any help, open an issue.

## Feature requests and bug reports

Feature requests and bug reports are always welcome, no matter how big or how small. To report something, create an issue on Github.
