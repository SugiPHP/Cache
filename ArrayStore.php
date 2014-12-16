<?php
/**
 * Array Store
 *
 * @package SugiPHP.Cache
 * @author  Plamen Popov <tzappa@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

/**
 * Array Store
 * Main purpose of this class is to be used in unit testing.
 * Note that no expiration time is implemented! Store will be flushed after the script is over.
 */
class ArrayStore implements StoreInterface
{
	protected $store = array();

	/**
	 * {@inheritdoc}
	 */
	public function add($key, $value, $ttl = 0)
	{
		if ($this->has($key)) {
			return false;
		}
		$this->store[$key] = $value;

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($key, $value, $ttl = 0)
	{
		$this->store[$key] = $value;

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($key)
	{
		return isset($this->store[$key]) ? $this->store[$key] : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has($key)
	{
		return isset($this->store[$key]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($key)
	{
		unset($this->store[$key]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
		$this->store = array();
	}
}
