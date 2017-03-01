StreamHttp library
========================
> The StreamHttp library defines an object-oriented layer for the HTTP specification.

[![Build Status](https://travis-ci.org/OsLab/streamHttp.svg?branch=master)](https://travis-ci.org/OsLab/streamHttp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OsLab/streamHttp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/OsLab/streamHttp/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/OsLab/streamHttp/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/OsLab/streamHttp/?branch=master)
[![Total Downloads](https://poser.pugx.org/oslab/stream-http/downloads)](https://packagist.org/packages/oslab/stream-http)
[![Latest Stable Version](https://poser.pugx.org/oslab/stream-http/v/stable)](https://packagist.org/packages/oslab/stream-http)
[![License](https://poser.pugx.org/oslab/stream-http/license)](https://packagist.org/packages/oslab/stream-http)

Introduction
-------------

StreamHttp has the goal is to be simple to use and use PSR7 interfaces for be compatible with other libraries.
StreamHttp just uses the native extensions of PHP in order to dispense with dependency like cCurl.

> :warning: This library is in state W.I.P (Work In Progress).

> Your contributions are welcome.

Installation
------------

### Step 1: Download StreamHttp using [Composer](http://getcomposer.org)

Require the bundle with composer:

    $ composer require oslab/oslab/stream-http

Or you can add it in the composer.json. Just check Packagist for the version you want to install (in the following example, we used "dev-master") and add it to your composer.json:

```json
    {
        "require": {
            "oslab/streamHttp": "dev-master"
        }
    }
```

## Credits

* [All contributors](https://github.com/OsLab/streamHttp/graphs/contributors)

## License

StreamHttp library is released under the MIT License, you agree to license your code under the [MIT license](LICENSE)
