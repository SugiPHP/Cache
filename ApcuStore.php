<?php
/**
 * APCu store.
 *
 * @package SugiPHP.Cache
 * @author  Plamen Popov <tzappa@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

class ApcuStore implements StoreInterface, IncrementorInterface
{
    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $ttl = 0)
    {
        $res = apcu_add($key, $value, $ttl);

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = 0)
    {
        $res = apcu_store($key, $value, $ttl);

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $success = null;
        $result = apcu_fetch($key, $success);

        if (!$success) {
            return null;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        if (!apcu_exists($key)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        apcu_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        apcu_clear_cache();
    }

    /**
     * {@inheritdoc}
     */
    public function inc($key, $step = 1)
    {
        return apcu_inc($key, $step);
    }

    /**
     * {@inheritdoc}
     */
    public function dec($key, $step = 1)
    {
        return apcu_dec($key, $step);
    }
}
