<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @category   tests
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

use SugiPHP\Cache\MemcachedStore as Store;

class MemcachedStoreTest extends PHPUnit_Framework_TestCase
{
	public static $store;

	public static function setUpBeforeClass()
	{
		$memcached = new Memcached();
		$memcached->addServer("127.0.0.1", 11211);
		static::$store = new Store($memcached);
	}

	public function setUp()
	{
		if (!static::$store->checkRunning()) {
		 	$this->markTestSkipped("Could not connect to Memcached");
		}
		static::$store->delete("phpunittestkey");
	}

	public function testCheckInstance()
	{
		$this->assertInstanceOf("\SugiPHP\Cache\StoreInterface", static::$store);
	}

	public function testReturnsNullWhenNotFound()
	{
		$this->assertNull(static::$store->get("phpunittestkey"));
	}

	public function testHasReturnsFalseIfNotFound()
	{
		$this->assertFalse(static::$store->has("phpunittestkey"));
	}

	public function testDeleteReturnsNullIfNotFound()
	{
		$this->assertNull(static::$store->delete("phpunittestkey"));
	}

	public function testSet()
	{
		$this->assertTrue(static::$store->set("phpunittestkey", "phpunittestvalue"));
	}

	public function testGet()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey"));
	}

	public function testNegativeTTL()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue", -1);
		$this->assertNull(static::$store->get("phpunittestkey"));
	}

	public function testTTL()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue", 1);
		$this->assertTrue(static::$store->has("phpunittestkey"));
		sleep(1);
		$this->assertFalse(static::$store->has("phpunittestkey"));
	}

	public function testTTLNotExpire()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue", 2);
		$this->assertTrue(static::$store->has("phpunittestkey"));
		sleep(1);
		$this->assertTrue(static::$store->has("phpunittestkey"));
	}

	public function testHas()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue", -1);
		$this->assertFalse(static::$store->has("phpunittestkey"));
		static::$store->set("phpunittestkey", "phpunittestvalue", 1);
		$this->assertTrue(static::$store->has("phpunittestkey"));
	}

	public function testDeleteReturnsTrue()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertTrue(static::$store->has("phpunittestkey"));
		static::$store->delete("phpunittestkey");
		$this->assertFalse(static::$store->has("phpunittestkey"));
	}

	public function testFlush()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertTrue(static::$store->has("phpunittestkey"));
		static::$store->flush();
		$this->assertFalse(static::$store->has("phpunittestkey"));
	}
}
