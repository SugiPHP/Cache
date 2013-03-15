<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

use Memcached;

class MemcachedStore implements StoreInterface, IncrementorInterface
{
	/**
	 * Memcached instance
	 */
	protected $memcached;

	/**
	 * Fix bug in memcached PHP module
	 * @see https://bugs.php.net/bug.php?id=51434
	 * @var boolean
	 */
	public $bug51434fix = true;

	/**
	 * Creates a Memcached store
	 * 
	 * @param Memcached $memcached
	 */
	public function __construct(Memcached $memcached)
	{
		$this->memcached = $memcached;
	}

	/**
	 * Creates MemcacheStore instance
	 * 
	 * @param  array $config Server Configurations
	 * @return MemcacheStore
	 */
	public static function factory(array $config = array())
	{
		$memcached = new Memcached();

		// empty config
		if (empty($config)) {
			$host = "127.0.0.1";
			$port = 11211;
			
			$memcached->addServer($host, $port);
		} elseif (empty($config[0])) {
			// only one server
			$host = empty($config["host"]) ? "127.0.0.1" : $config["host"];
			$port = empty($config["port"]) ? 11211 : $config["port"];
			$weight = empty($config["weight"]) ? 1 : $config["weight"];

			$memcached->addServer($host, $port, $weight);		
		} else {
			// multiple servers
			$memcached->addServers($config);
		}

		// The code using a store should work no matter if the store is running or not
		// Check is the memcache store is working with checkRunning() method
		return new MemcachedStore($memcached);
	}

	/**
	 * @inheritdoc
	 */
	function add($key, $value, $ttl = 0)
	{
		return $this->memcached->add($key, $value, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	function set($key, $value, $ttl = 0)
	{
		return $this->memcached->set($key, $value, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	function get($key)
	{
		$result = $this->memcached->get($key);

		if (($result === false) and ($this->memcached->getResultCode() === \Memcached::RES_NOTFOUND)) {
			return null;
		}
		
		return $result;
	}

	/**
	 * @inheritdoc
	 */
	function has($key)
	{
		$result = $this->memcached->get($key);
		if (($result === false) and ($this->memcached->getResultCode() === \Memcached::RES_NOTFOUND)) {
			return false;
		}
		return true;
	}

	/**
	 * @inheritdoc
	 */
	function delete($key)
	{
		$this->memcached->delete($key);
	}

	/**
	 * @inheritdoc
	 */
	function flush()
	{
		$this->memcached->flush();
	}

	/**
	 * @inheritdoc
	 */
	function inc($key, $step = 1)
	{
		// due to a bug in memcached PHP module 
		// https://bugs.php.net/bug.php?id=51434
		// we'll check if the $key has a non numeric value
		if ($this->bug51434fix) {
			$value = $this->get($key);
			if (is_null($value) or !is_numeric($value)) {
				return false;
			}
		}
		$val = $this->memcached->increment($key, $step);
		// on some servers it will return 0 instead of FALSE
		return ($val) ? $val : false;
	}

	/**
	 * @inheritdoc
	 */
	function dec($key, $step = 1)
	{
		// due to a bug in memcached PHP module 
		// https://bugs.php.net/bug.php?id=51434
		// we'll check if the $key has a non numeric value
		if ($this->bug51434fix) {
			$value = $this->get($key);
			if (is_null($value) or !is_numeric($value)) {
				return false;
			}
		}
		$val = $this->memcached->decrement($key, $step);
		// on some servers it will return 0 instead of FALSE
		return ($val) ? $val : false;
	}

	/**
	 * Checks is the memcache server is running
	 * 
	 * @return boolean
	 */
	public function checkRunning()
	{
		$servers = $this->memcached->getVersion();
	
		// this happens when no servers were added
		if (!$servers) {
			return false;
		}
	
		// at least one server should be running
		foreach ($servers as $server) {
			if ($server != "255.255.255") {
				return true;
			}
		}
	
		return false;
	}
}
