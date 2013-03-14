<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @category   tests
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

use SugiPHP\Cache\Cache;
use SugiPHP\Cache\ArrayStore;
use SugiPHP\Cache\ApcStore;
use SugiPHP\Cache\MemcachedStore;

class CacheTest extends PHPUnit_Framework_TestCase
{
	public static $store;

	public static function setUpBeforeClass()
	{
		// static::$store = new Cache(new ArrayStore());
		// static::$store = new Cache(new ApcStore());
		static::$store = new Cache(MemcachedStore::factory());
	}

	public function setUp()
	{
		static::$store->delete("phpunittestkey");
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
		// get not existg
		$this->assertNull(static::$store->get("phpunittestkey"));
		// with default value
		$this->assertEquals("default", static::$store->get("phpunittestkey", "default"));
		// set it
		static::$store->set("phpunittestkey", "phpunittestvalue");
		// get it
		$this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey"));
		// get with default value
		$this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey", "default"));
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

	public function testInc()
	{
		// returns false on failure
		$this->assertFalse(static::$store->inc("phpunittestkey"));
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertFalse(static::$store->inc("phpunittestkey"));
		// increments
		static::$store->set("phpunittestkey", 7);
		$this->assertEquals(8, static::$store->inc("phpunittestkey"));
		$this->assertEquals(10, static::$store->inc("phpunittestkey", 2));
	}

	public function testDec()
	{
		// returns false on failure
		$this->assertFalse(static::$store->dec("phpunittestkey"));
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertFalse(static::$store->dec("phpunittestkey"));
		// increments
		static::$store->set("phpunittestkey", 7);
		$this->assertEquals(6, static::$store->dec("phpunittestkey"));
		$this->assertEquals(4, static::$store->dec("phpunittestkey", 2));
	}
}
