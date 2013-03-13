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
	 * Stores an item in the cache for a specified period of time.
	 *
	 * @param  string $key 
	 * @param  mixed $value The value to be stored.
	 * @param  integer $ttl Time to live in seconds. 0 means to store it for a maximum time possible
	 * @return boolean TRUE if the value is set, FALSE if storing failed
	 */
	function set($key, $value, $ttl = 0);

	/**
	 * Retrieve an item from the cache.
	 * 
	 * @param  string $key
	 * @return mixed The value that was stored, or NULL if the value was not set or expired or not available for some other reason
	 */
	function get($key);

	/**
	 * Determine if an item exists in the cache.
	 * 
	 * @param  string $key
	 * @return boolean
	 */
	function has($key);

	/**
	 * Removes an item from the cache.
	 * 
	 * @param  string $key
	 */
	function delete($key);

	/**
	 * Removes all items from the cache.
	 */
	function flush();
}
