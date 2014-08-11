<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

class Cache
{
	/**
	 * The driver used by a cache.
	 * @var StoreInterface
	 */
	protected $driver;

	/**
	 * Key prefix to use.
	 * @var string
	 */
	protected $prefix = "";

	/**
	 * Class constructor.
	 *
	 * @param StoreInterface $driver
	 */
	public function __construct(StoreInterface $driver)
	{
		$this->driver = $driver;
	}

	/**
	 * Sets key prefix.
	 *
	 * @param string $prefix
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	/**
	 * Returns key prefix.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * Stores an item in the cache for a specified period of time only if it is not already stored.
	 * Cache::add() is similar to Cache::set(), but the operation fails if the key already exists.
	 *
	 * @param  string  $key
	 * @param  mixed  $value The value to be stored.
	 * @param  integer $ttl Time to live in seconds. 0 means to store it for a maximum time possible
	 * @return boolean TRUE if the value is set, FALSE on failure
	 */
	public function add($key, $value, $ttl = 0)
	{
		$key = $this->prefix.$key;

		return $this->driver->add($key, $value, $ttl);
	}

	/**
	 * Stores an item in the data store
	 * Cache::set() is similar to Cache::add(), but the operation will not fail if the key already exist.
	 *
	 * @param  string $key The key under which to store the value
	 * @param  mixed $value The value to store
	 * @param  integer $ttl Expiration time in seconds, after which the value is invalidated (deleted)
	 * @return boolean TRUE on success or FALSE on failure
	 */
	public function set($key, $value, $ttl = 0)
	{
		$key = $this->prefix.$key;

		return $this->driver->set($key, $value, $ttl);
	}

	/**
	 * Fetches a stored variable from the cache
	 *
	 * @param  string $key The key used to store the value
	 * @return mixed Returns NULL if the key does not exist in the store or the value was expired (see $ttl)
	 */
	public function get($key, $defaultValue = null)
	{
		$key = $this->prefix.$key;
		$result = $this->driver->get($key);

		return is_null($result) ? $defaultValue : $result;
	}

	/**
	 * Checks if the key exists
	 *
	 * @param  string $key
	 * @return boolean TRUE if the key exists, otherwise FALSE
	 */
	public function has($key)
	{
		$key = $this->prefix.$key;

		return $this->driver->has($key);
	}

	/**
	 * Removes a stored variable from the cache
	 *
	 * @param string $key
	 */
	public function delete($key)
	{
		$key = $this->prefix.$key;

		$this->driver->delete($key);
	}

	/**
	 * Invalidate all items in the cache
	 */
	public function flush()
	{
		$this->driver->flush();
	}

	/**
	 * Increment numeric item's value.
	 * If there is no such key or the stored value is not numeric FALSE is returned
	 *
	 * @param  string $key
	 * @param  integer $step
	 * @return integer or FALSE on failure
	 */
	public function inc($key, $step = 1)
	{
		$key = $this->prefix.$key;

		if ($this->driver instanceof IncrementorInterface) {
			return $this->driver->inc($key, $step);
		}

		$value = $this->driver->get($key);
		if (is_null($value) || !is_numeric($value)) {
			return false;
		}
		$newValue = $value + $step;

		return ($this->driver->set($key, $newValue)) ? $newValue : false;
	}

	/**
	 * Decrements numeric item's value.
	 * If there is no such key or the stored value is not numeric FALSE is returned
	 *
	 * @param  string $key
	 * @param  integer $step
	 * @return integer or FALSE on failure
	 */
	public function dec($key, $step = 1)
	{
		$key = $this->prefix.$key;

		if ($this->driver instanceof IncrementorInterface) {
			return $this->driver->dec($key, $step);
		}

		$value = $this->driver->get($key);
		if (is_null($value) || !is_numeric($value)) {
			return false;
		}
		$newValue = $value - $step;

		return ($this->driver->set($key, $newValue)) ? $newValue : false;
	}
}
