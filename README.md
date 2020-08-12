FlameCore Container
===================

[![Latest Stable](http://img.shields.io/packagist/v/flamecore/container.svg)](https://packagist.org/p/flamecore/container)
[![Build Status](https://img.shields.io/travis/com/flamecore/flamecore-container.svg)](https://travis-ci.com/github/flamecore/flamecore-container)
[![Scrutinizer](http://img.shields.io/scrutinizer/g/flamecore/flamecore-container.svg)](https://scrutinizer-ci.com/g/flamecore/flamecore-container)
[![Coverage](http://img.shields.io/scrutinizer/coverage/g/flamecore/flamecore-container.svg)](https://scrutinizer-ci.com/g/flamecore/flamecore-container)
[![License](http://img.shields.io/packagist/l/flamecore/container.svg)](https://www.flamecore.org/projects/container)

This library provides a simple and lightweight Dependency Injection Container.


Features
--------

* **Interfaces:** Extended container interfaces compatible with *PSR-11*

    * `ModifiableContainerInterface` exposes methods to write and remove container entries.

    * `DefinableContainerInterface` exposes methods to define container entries.

    * `FactoryContainerInterface` exposes a method to create new object instances from a factory entry.

    * `LockableContainerInterface` exposes methods to lock and unlock container entries.

* **Classes:** Advanced and feature-complete container implementations based on the interfaces

    * `Container` implements common features like defining, writing, instantiating and removing container entries.

    * `LockableContainer` extends the above container to enable locking and unlocking of container entries.


Usage
-----

To make use of the API, include the vendor autoloader and use the classes:

```php
namespace Acme\MyApplication;

use FlameCore\Container\Container;

$container = new Container();
```

You can use `get()` and `has()` on the container as usual. You can also `set()` and `remove()` entries.

```php
$container->set('foo', new Configuration(...));
if ($container->has('foo')) {
    $value = $container->get('foo'); // Returns Acme\MyApplication\Configuration object
    $container->remove('foo');
}
```

You can also use object factories:

```php
$container->set('bar', function () {
    return new Configuration(...);
});
$value2 = $container->get('bar'); // Returns Acme\MyApplication\Configuration object
```

You can also get a fallback value if an entry is not set:

```php
$value3 = $container->getOr('foobar', function () {
    return new Fallback(...);
}); // Returns Acme\MyApplication\Fallback object
```


Installation
------------

[Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) if you don't already have it present on your system.

To install the library, run the following command and you will get the latest version:

    $ php composer.phar require flamecore/container


Requirements
------------

* You must have at least PHP version 7.2 installed on your system.


Contributors
------------

If you want to contribute, please see the [CONTRIBUTING](CONTRIBUTING.md) file first.

Thanks to the contributors:

* Christian Neff (secondtruth)
