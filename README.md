Cache
=====

SugiPHP cache component provides a simple and unified API for several caching systems like APC and Memcached.
Future versions will include support for file based and DB stores.

One of the most important features of the caching systems is invalidating items after a specified period of time.
This will give you the ability to cache some time consuming queries like the total number of adverts in your site,
and not worry about if some advert is deleted or some adverts are posted, but instead set a time after which that
number is invalidated and needs to be refreshed.

One other feature is that no matter if the store is actually running (caching items) or not it will not produce 
any errors or exceptions. Instead it will return NULL on any get requests. In the above example this means that 
your code will be fooled to count adverts every time, maybe slowing down performance, but still working. Your
code will still work on some development or testing environments where no cache is available nor is needed.

Usage
-----

Caching is done by setting a key-value pairs in a store. 

- Store a value in a cache
```
$cache->set("key", "value", 60); // store a value for 60 seconds
// or
$cache->set("foo", "bar"); // store a value for a maximum allowed time
```

- Retrieve value from the cache
```
$cache->get("key"); // this will return "value" if 60 seconds are not passed and NULL after that
$cache->get("baz"); // will return NULL
```

- Delete a value
```
$cache->delete("key");
```

- Check value is set
```
$cache->has("key"); // will return TRUE if the "key" was set and the expiration time was not passed, and FALSE otherwise
```
