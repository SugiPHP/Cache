<?php
/**
 * @package    SugiPHP
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
	 * Creates MemcacheStore instance
	 * 
	 * @param  array $config Server Configurations
	 * @return MemcacheStore
	 */
	public static function factory(array $config = array())
	{
		$memcache = new Memcache();

		$host = empty($config["host"]) ? "127.0.0.1" : $config["host"];
		$port = empty($config["port"]) ? 11211 : $config["port"];

		$this->connected = $memcache->connect($host, $port);

		// The code using a store should work no matter if the store is running or not
		// Check is the memcache store is working with checkRunning() method
		return new MemcacheStore($memcache);
	}

	/**
	 * @inheritdoc
	 */
	function add($key, $value, $ttl = 0)
	{
		return $this->memcache->add($key, $value, 0, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	function set($key, $value, $ttl = 0)
	{
		return $this->memcache->set($key, $value, 0, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	function get($key)
	{
		$result = $this->memcache->get($key);

		return ($result === false) ? null : $result;
	}

	/**
	 * @inheritdoc
	 */
	function has($key)
	{
		return (!is_null($this->memcache->get($key)));
	}

	/**
	 * @inheritdoc
	 */
	function delete($key)
	{
		$this->memcache->delete($key);
	}

	/**
	 * @inheritdoc
	 */
	function flush()
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
