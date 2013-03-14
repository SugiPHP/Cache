<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

class ApcStore implements StoreInterface
{
	// for some optimization reasons in APC it does not invalidate
	// data on same request. @see  https://bugs.php.net/bug.php?id=58084
	// To fix this behavior we'll use cache to store items along with timestamps
	protected $ttls = array();
	protected $ttlFix = false;

	public function __construct(array $config = array())
	{
		// do we need a TTL fix
		// usually need to be set to true only for unit testing
		if (!empty($config["ttl_fix"])) {
			$this->ttlFix = true;
		}
	}

	/**
	 * @inheritdoc
	 */
	function set($key, $value, $ttl = 0)
	{
		$res = apc_store($key, $value, $ttl);
		if ($this->ttlFix) {
			unset($this->ttls[$key]);
			// fixing ttl only if it is set
			if ($res and $ttl) {
				$this->ttls[$key] = microtime(true) + $ttl;
			}
		}
		return $res;
	}

	/**
	 * @inheritdoc
	 */
	function get($key)
	{
		$result = apc_fetch($key, $success);

		if (!$success) {
			return null;
		}

		if ($this->ttlFix) {
			if (isset($this->ttls[$key]) and $this->ttls[$key] < microtime(true)) {
				unset($this->ttls[$key]);
				return null;
			}
		}

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	function has($key)
	{
		if (!apc_exists($key)) {
			return false;
		}
		
		if ($this->ttlFix) {
			if (isset($this->ttls[$key]) and $this->ttls[$key] < microtime(true)) {
				unset($this->ttls[$key]);
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritdoc
	 */
	function delete($key)
	{
		if (apc_delete($key)) {
			if ($this->ttlFix) {
				unset($this->ttls[$key]);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	function flush()
	{
		if (apc_clear_cache("user")) {
			if ($this->ttlFix) {
				unset($this->ttls);
			}
		}
	}

	/**
	 * Checks APC is running
	 * 
	 * @return boolean
	 */
	public function checkRunning()
	{
		return (function_exists("apc_store") and ini_get("apc.enabled") and ((PHP_SAPI != "cli") or ini_get("apc.enable_cli")));
	}
}
