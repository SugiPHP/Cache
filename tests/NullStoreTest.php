<?php
/**
 * Tests for NullStore class.
 *
 * @package SugiPHP.Cache
 * @author  Plamen Popov <tzappa@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

use SugiPHP\Cache\NullStore as Store;
use PHPUnit_Framework_TestCase;

class NullStoreTest extends PHPUnit_Framework_TestCase
{
    public static $store;

    public static function setUpBeforeClass()
    {
        static::$store = new Store();
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
        $this->assertFalse(static::$store->set("phpunittestkey", "phpunittestvalue"));
    }

    public function testGet()
    {
        static::$store->set("phpunittestkey", "phpunittestvalue");
        $this->assertNull(static::$store->get("phpunittestkey"));
    }

    public function testHas()
    {
        static::$store->set("phpunittestkey", "phpunittestvalue");
        $this->assertFalse(static::$store->has("phpunittestkey"));
    }

    public function testFlush()
    {
        static::$store->set("phpunittestkey", "phpunittestvalue");
        static::$store->flush();
        $this->assertFalse(static::$store->has("phpunittestkey"));
    }
}
