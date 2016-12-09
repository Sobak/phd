# PhD: PHP DocBook Rendering System
###### Copyright(c) 2007-2016 The PHP Documentation Team
-----

PhD is PHP's very own DocBook 5 rendering system. It is used to convert the PHP
Manual and PEAR Documentation into different output formats like XHTML, PDF, Man
pages and CHM.

## Installation
[Composer](https://getcomposer.org/) is needed to install PhD. Then all you need to
do is execute following command:

```
composer global require php/phd
```

> Remember that you need to add `~/.composer/vendor/bin` (or its Windows counterpart)
> to your `PATH`.

### Installation for local development
You can just clone this repository and run either `bin/phd` or `render.php` directly.

The minimal required PHP version is 5.3 with these extensions enabled: DOM, XMLReader
and SQLite3.

## Usage
After installing PhD you can use the `phd` command
to render the documentations. To see a list of available packages/formats
use the `phd -l` command. By default `phd` use `./output` for the rendered files.

The `phd` command optionally takes more arguments.
For information about those arguments please type `phd -h`
