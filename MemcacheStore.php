<?php
/**
 * Memcache Store. This store is deprecated. Use Memchached store instead.
 *
 * @package    SugiPHP.Cache
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

use Memcache;

class MemcacheStore implements StoreInterface
{
    /**
     * Memcache instance
     */
    protected $memcache;
    protected $connected = false;

    /**
     * Creates a Memcache store
     *
     * @param Memcache $memcache
     */
    public function __construct(Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * Creates MemcacheStore instance.
     *
     * @param array $config Server Configurations
     *
     * @return MemcacheStore
     */
    public static function factory(array $config = array())
    {
        $memcache = new Memcache();

        $host = empty($config["host"]) ? "127.0.0.1" : $config["host"];
        $port = empty($config["port"]) ? 11211 : $config["port"];

        $connected = $memcache->connect($host, $port);

        // The code using a store should work no matter if the store is running or not
        // Check is the memcache store is working with checkRunning() method
        $store = new MemcacheStore($memcache);
        $store->connected = $connected;

        return $store;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        return $this->memcache->add($key, $value, 0, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        return $this->memcache->set($key, $value, 0, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $result = $this->memcache->get($key);

        return ($result === false) ? null : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return (!is_null($this->memcache->get($key)));
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        $this->memcache->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->memcache->flush();
    }

    /**
     * Checks is the memcache server is running
     *
     * @return boolean
     */
    public function checkRunning()
    {
        return $this->connected;
    }
}
