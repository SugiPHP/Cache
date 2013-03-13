<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

use Memcached;

class MemcachedStore implements StoreInterface
{
	/**
	 * Memcached instance
	 */
	protected $memcached;

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



	// /**
	//  * Creates MemcacheStore instance
	//  * 
	//  * @param  array $config Server Configurations
	//  * @return MemcacheStore
	//  */
	// public static function factory(array $config = array())
	// {
	// 	$memcached = new \Memcached();

	// 	// empty config
	// 	if (empty($config)) {
	// 		$host = "127.0.0.1";
	// 		$port = 11211;
	// 		$weight = 1;
			
	// 		$memcached->addServer($host, $port, $weight);		
	// 	} elseif (count($config) == 1) {
	// 		// only one server
	// 		$server = $config[0];
	// 		$host = empty($server["host"]) ? "127.0.0.1" : $server["host"];
	// 		$port = empty($server["port"]) ? 11211 : $server["port"];
	// 		$weight = empty($server["weight"]) ? 1 : $server["weight"];

	// 		$memcached->addServer($host, $port, $weight);		
	// 	} else {
	// 		// multiple servers
	// 		$memcached->addServers($config);
	// 	}

	// 	// The code using a store should work no matter if the store is running or not
	// 	// Check is the memcache store is working with checkRunning() method
	// 	return new MemcachedStore($memcached);
	// }

	// public function inc($key, $offset = 1, $defaultValue = 0, $ttl = 0)
	// {
	// 	$inc = $this->memcached->increment($key, $offset);
	// 	// there was no key, or on some other error
	// 	if ($inc === false) {
	// 		// we need custom initial value to be set
	// 		return ($this->set($key, $defaultValue, $ttl) === false) ? false : $defaultValue;
	// 	}

	// 	return $inc;
	// }
}
