# Cache

[![Build Status](https://scrutinizer-ci.com/g/SugiPHP/Cache/badges/build.png?b=master)](https://scrutinizer-ci.com/g/SugiPHP/Cache/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SugiPHP/Cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SugiPHP/Cache/?branch=master)

SugiPHP cache component provides a simple and unified API for several caching systems like APC, Memcached and file based cache.

One of the most important features of the caching systems is invalidating items after a specified period of time.
This will give you the ability to cache some time consuming queries like the total number of adverts in your site,
and not worry about if some advert is deleted or some adverts are posted, but instead set a time after which that
number is invalidated and needs to be refreshed.

One other feature is that no matter if the store is actually running (caching items) or not it will not produce
any errors or exceptions. Instead it will return NULL on any get requests. In the above example this means that
your code will be fooled to count adverts every time, maybe slowing down performance, but still working. Your
code will still work on some development or testing environments where no cache is available nor is needed.

## Installation

```bash
composer require sugiphp/cache ~1.0
```

### Settings

 - Using APC (APCu) as a cache:
```php
<?php
use SugiPHP\Cache\Cache;
use SugiPHP\Cache\ApcStore;

$apcStore = new ApcStore();
$cache = new Cache($apcStore);

$cache->add("foo", "bar");
?>
```

 - Using file-based cache:
```php
<?php
use SugiPHP\Cache\Cache;
use SugiPHP\Cache\FileStore;

$cacheDir = "/path/to/tmp";
$fileStore = new FileStore($cacheDir);
$cache = new Cache($fileStore);
?>
```

 - Using Memcached:
```php
<?php
use SugiPHP\Cache\Cache;
use SugiPHP\Cache\MemcachedStore;

// make a regular Memcached instance
$memcached = new Memcached();
// connect to a memcached server
$memcached->addServer("127.0.0.1", 11211);

// make a store
$mcStore = new MemcachedStore($memcached);
$cache = new Cache($mcStore);
?>
```

 - NullStore and ArrayStore

Those two classes are not real cache storages, but they are usefull on development environments
and for unit tests. ArrayStore caches values only for a lifetime of the script, and thus the
expiration time is not implemented. All store values will be flushed after the script is over.

```php
<?php

use SugiPHP\Cache\Cache;
use SugiPHP\Cache\ArrayStore;

$cache = new Cache(new ArrayStore());
?>
```

NullStore is actually a fake store. It is used to check your code is operational even if there is no cache storages available or if there is a problem with existing ones, e.g. no space left on the server or there is no connection with Memcached.
Other use is when you wish your code to work without any caching for a while. Especially usefull on development when any caching mechanism can be a disadvantage.
```php
<?php

use SugiPHP\Cache\Cache;
use SugiPHP\Cache\NullStore;

$cache = new Cache(new NullStore());

$cache->set("foo", "bar");
$cache->get("foo"); // will return NULL
?>
```

## Usage

Caching is done by setting a key-value pairs in a store.

### Store a value in a cache
```php
<?php
$cache->set("foo", "bar");    // store a value for a maximum allowed time
$cache->set("foo", "foobar"); // store a new value with the same key
?>
```

#### Add a value if it is not already been stored
```php
<?php
$cache->add("key", "foo");    // this will store a value
$cache->add("key", "foobar"); // this will fail
?>
```

### Retrieve value from the cache
```php
<?php
$cache->set("key", "value", 60); // store a value for 60 seconds
$cache->get("key"); // this will return "value" if 60 seconds are not passed and NULL after that
$cache->get("baz"); // will return NULL
?>
```

### Delete a value
```php
<?php
$cache->delete("key");
?>
```

### Check value is set
```php
<?php
$cache->has("key"); // will return TRUE if the "key" was set and the expiration time was not passed, and FALSE otherwise
?>
```

### Increment / decrement value
```php
<?php
$cache->set("num", 1); // store a numeric value
$cache->inc("num"); // will return 2
$cache->inc("num", 10); // will return 12
$cache->dec("num"); // will return 11
?>
```
