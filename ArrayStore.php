<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

/**
 * Array Storage
 * Main purpose of this class is to be used in unit testing.
 * Note that no expiration time is implemented! Store will be flushed after the script is over. 
 */
class ArrayStore implements StoreInterface
{
	protected $store = array();

	function set($key, $value, $ttl = 0)
	{
		$this->store[$key] = $value;
		return true;
	}

	function get($key)
	{
		return isset($this->store[$key]) ? $this->store[$key] : null;
	}

	function has($key)
	{
		return isset($this->store[$key]);
	}

	function delete($key)
	{
		unset($this->store[$key]);
	}

	function flush()
	{
		$this->store = array();
	}
}
