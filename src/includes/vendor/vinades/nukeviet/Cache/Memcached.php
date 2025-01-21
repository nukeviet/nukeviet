<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Cache;

use Memcached as GMemcached;

/**
 * NukeViet\Cache\Memcached
 *
 * @package NukeViet\Cache
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Memcached extends Cache
{
    private $_Cache;

    /**
     * __construct()
     *
     * @param string $Host
     * @param int    $Port
     * @param string $Lang
     * @param string $Cache_Prefix
     */
    public function __construct($Host, $Port, $Lang, $Cache_Prefix)
    {
        parent::__construct($Lang, $Cache_Prefix);

        $this->_Cache = new GMemcached();
        $this->_Cache->addServer($Host, $Port);
    }

    /**
     * delAll()
     */
    public function delAll()
    {
        $this->_Cache->flush();
    }

    /**
     * delMod()
     *
     * @param string $module_name
     */
    public function delMod($module_name)
    {
        $AllKeys = $this->_Cache->getAllKeys();
        foreach ($AllKeys as $_key) {
            if (preg_match('/^' . preg_quote($module_name) . '\_/', $_key)) {
                $this->_Cache->delete($_key);
            }
        }
    }

    /**
     * getItem()
     *
     * @param string $module_name
     * @param string $filename
     * @return mixed
     */
    public function getItem($module_name, $filename)
    {
        // Note: $ttl not check in Memcached cache
        return $this->_Cache->get($module_name . '_' . md5($filename));
    }

    /**
     * setItem()
     *
     * @param string $module_name
     * @param string $filename
     * @param string $content
     * @param int    $ttl
     * @return mixed
     */
    public function setItem($module_name, $filename, $content, $ttl = 0)
    {
        return $this->_Cache->set($module_name . '_' . md5($filename), $content, $ttl);
    }

    /**
     * db()
     *
     * @param string $sql
     * @param string $key
     * @param string $modname
     * @param string $lang
     * @param int    $ttl
     * @return array
     */
    public function db($sql, $key, $modname, $lang = '', $ttl = 0)
    {
        if (empty($sql)) {
            return [];
        }

        if (empty($lang)) {
            $lang = $this->_Lang;
        }

        $cache_key = $modname . '_' . $lang . '_' . md5($sql . '_' . $this->_Cache_Prefix);

        $list = $this->_Cache->get($cache_key);
        if ($list) {
            return $list;
        }

        $list = parent::getList($sql, $key);
        if ($list === false) {
            return [];
        }

        $this->_Cache->set($cache_key, $list, $ttl);

        return $list;
    }

    /**
     * delItem()
     *
     * @param string $module_name
     * @param string $filename
     * @return bool
     */
    public function delItem($module_name, $filename)
    {
        return $this->_Cache->delete($module_name . '_' . md5($filename));
    }
}
