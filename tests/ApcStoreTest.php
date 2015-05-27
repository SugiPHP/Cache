<?php
/**
 * Tests for ApcStore class.
 *
 * @package SugiPHP.Cache
 * @author  Plamen Popov <tzappa@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

use SugiPHP\Cache\ApcStore as Store;
use PHPUnit_Framework_TestCase;

class ApcStoreTest extends PHPUnit_Framework_TestCase
{
    public static $store;

    public static function setUpBeforeClass()
    {
        if (!function_exists("apc_store")) {
            static::markTestSkipped("APC is not available");
        }
        static::$store = new Store(array("ttl_fix" => true));
        if (!static::$store->checkRunning()) {
            static::markTestSkipped("APC is not working");
        }
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

    public function testAdd()
    {
        $this->assertTrue(static::$store->add("phpunittestkey", "phpunittestvalue"));
        $this->assertFalse(static::$store->add("phpunittestkey", "phpunittestvalue2"));
    }

    public function testIncNonExisting()
    {
        // returns false
        $this->assertFalse(static::$store->inc("phpunittestkey"));
        // check increment does not set value
        $this->assertFalse(static::$store->has("phpunittestkey"));
    }

    public function testIncNonNumeric()
    {
        static::$store->set("phpunittestkey", "phpunittestvalue");
        // returns false on non numeric values
        $this->assertFalse(static::$store->inc("phpunittestkey"));
        // does not modifies value
        $this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey"));
    }

    public function testInc()
    {
        static::$store->set("phpunittestkey", 7);
        $this->assertEquals(8, static::$store->inc("phpunittestkey"));
        $this->assertEquals(10, static::$store->inc("phpunittestkey", 2));
    }

    public function testDecNonExisting()
    {
        // returns false
        $this->assertFalse(static::$store->dec("phpunittestkey"));
        // check increment does not set value
        $this->assertFalse(static::$store->has("phpunittestkey"));
    }

    public function testDecNonNumeric()
    {
        static::$store->set("phpunittestkey", "phpunittestvalue");
        // returns false on non numeric values
        $this->assertFalse(static::$store->dec("phpunittestkey"));
        // does not modifies value
        $this->assertEquals("phpunittestvalue", static::$store->get("phpunittestkey"));
    }

    public function testDec()
    {
        static::$store->set("phpunittestkey", 7);
        $this->assertEquals(6, static::$store->dec("phpunittestkey"));
        $this->assertEquals(4, static::$store->dec("phpunittestkey", 2));
    }
}
