<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

class FileStore implements StoreInterface
{
	/**
	 * Path to directory where cache files will be saved.
	 */
	protected $path;

	/**
	 * Creates a File store
	 *
	 * @param string
	 */
	public function __construct($path)
	{
		$this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

	/**
	 * @inheritdoc
	 */
	public function add($key, $value, $ttl = 0)
	{
		if ($this->has($key)) {
			return false;
		}

		return $this->set($key, $value, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	public function set($key, $value, $ttl = 0)
	{
		$file = $this->filename($key);
		$expire = ($ttl) ? time() + $ttl : "9999999999";
		// serializing is done mainly to distinguish type of the $value - string, number, etc.
		$contents = $expire.serialize($value);

		return (boolean) (@file_put_contents($file, $contents, LOCK_EX));
	}

	/**
	 * @inheritdoc
	 */
	public function get($key)
	{
		$file = $this->filename($key);
		if (!is_file($file)) {
			return null;
		}

		$contents = file_get_contents($file);

		// check TTL
		$expire = substr($contents, 0, 10);
		if ($expire < time()) {
			// if cache is expired delete cache file
			$this->delete($key);

			return null;
		}

		return unserialize(substr($contents, 10));
	}

	/**
	 * @inheritdoc
	 */
	public function has($key)
	{
		$res = $this->get($key);
		return (is_null($res)) ? false : true;
	}

	/**
	 * @inheritdoc
	 */
	public function delete($key)
	{
		@unlink($this->filename($key));
	}

	/**
	 * @inheritdoc
	 */
	public function flush()
	{
		$files = glob($this->path."*.cache");
		if ($files) {
			foreach ($files as $file) {
				@unlink($file);
			}
		}
	}

	/**
	 * Generates a filename based on the $key parameter
	 *
	 * @param  string $key
	 * @return string
	 */
	protected function filename($key)
	{
		return $this->path.md5($key).".cache";
	}
}
