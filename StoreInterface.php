<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

/**
 * Cache Store Interface
 */
interface StoreInterface
{
	/**
	 * Stores an item in the cache for a specified period of time only if it is not already stored.
	 * StoreInterface::add() is similar to StoreInterface::set(), but the operation fails if the key
	 * already exists.
	 *
	 * @param  string  $key
	 * @param  mixed  $value The value to be stored.
	 * @param  integer $ttl Time to live in seconds. 0 means to store it for a maximum time possible
	 * @return boolean TRUE if the value is set, FALSE on failure
	 */
	public function add($key, $value, $ttl = 0);

	/**
	 * Stores an item in the cache for a specified period of time.
	 * StoreInterface::set() is similar to StoreInterface::add(), but the operation will not fail if
	 * the key already exist.
	 *
	 * @param  string $key
	 * @param  mixed $value The value to be stored.
	 * @param  integer $ttl Time to live in seconds. 0 means to store it for a maximum time possible
	 * @return boolean TRUE if the value is set, FALSE if storing failed
	 */
	public function set($key, $value, $ttl = 0);

	/**
	 * Retrieve an item from the cache.
	 *
	 * @param  string $key
	 * @return mixed The value that was stored, or NULL if the value was not set or expired or not
	 * available for some other reason
	 */
	public function get($key);

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param  string $key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Removes an item from the cache.
	 *
	 * @param  string $key
	 * @return void
	 */
	public function delete($key);

	/**
	 * Removes all items from the cache.
	 *
	 * @return void
	 */
	public function flush();
}
