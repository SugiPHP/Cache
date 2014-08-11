<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

/**
 * Null Store
 * This is actually a fake store. It is used to check your code is not breaking
 * if there is no other storages available, or if there is a problem with existing
 * ones, e.g. no space left on the server or there is no connection with Memcached.
 * Other use is when you wish your code to work without any caching for a while.
 */
class NullStore implements StoreInterface
{
	/**
	 * @inheritdoc
	 */
	public function add($key, $value, $ttl = 0)
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function set($key, $value, $ttl = 0)
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function get($key)
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function has($key)
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function delete($key)
	{
		//
	}

	/**
	 * @inheritdoc
	 */
	public function flush()
	{
		//
	}
}
