<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @category   tests
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache\Test;

use SugiPHP\Cache\ArrayStore as Store;
use PHPUnit_Framework_TestCase;

class ArrayStoreTest extends PHPUnit_Framework_TestCase
{
	public static $store;

	public static function setUpBeforeClass()
	{
		static::$store = new Store();
	}

	public function tearDown()
	{
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

	public function testSet()
	{
		$this->assertTrue(static::$store->set("phpunittestkey", "phpunittestvalue"));
	}

	public function testHas()
	{
		$this->assertFalse(static::$store->has("phpunittestkey"));
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertTrue(static::$store->has("phpunittestkey"));
	}

	public function testGet()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey"));
	}

	public function testDelete()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		static::$store->delete("phpunittestkey");
		$this->assertNull(static::$store->get("phpunittestkey"));
	}

	public function testFlush()
	{
		static::$store->set("phpunittestkey", "phpunittestvalue");
		$this->assertTrue(static::$store->has("phpunittestkey"));
		static::$store->flush();
		$this->assertFalse(static::$store->has("phpunittestkey"));
	}

	public function testAdd()
	{
		$this->assertTrue(static::$store->add("phpunittestkey", "phpunittestvalue"));
		$this->assertFalse(static::$store->add("phpunittestkey", "phpunittestvalue2"));
	}
}
