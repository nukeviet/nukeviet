<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Memcached
{
    private $_Lang = 'vi';

    private $_Cache_Prefix = '';

    private $_Db;

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
        $this->_Lang = $Lang;
        $this->_Cache_Prefix = $Cache_Prefix;
        $this->_Cache = new GMemcached();
        $this->_Cache->addServer($Host, $Port);
    }

    /**
     * delAll()
     *
     * @param bool $sys
     */
    public function delAll($sys = true)
    {
        $this->_Cache->flush();
    }

    /**
     * delMod()
     *
     * @param string $module_name
     * @param string $lang
     */
    public function delMod($module_name, $lang = '')
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
     * @param int    $ttl
     * @return mixed
     */
    public function getItem($module_name, $filename, $ttl = 0)
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
     * setDb()
     *
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->_Db = $db;
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
        $_rows = [];

        if (empty($sql)) {
            return $_rows;
        }

        if (empty($lang)) {
            $lang = $this->_Lang;
        }

        $cache_key = $modname . '_' . $lang . '_' . md5($sql . '_' . $this->_Cache_Prefix);

        if (!($_rows = $this->_Cache->get($cache_key))) {
            if (($result = $this->_Db->query($sql)) !== false) {
                $a = 0;
                while ($row = $result->fetch()) {
                    $key2 = (!empty($key) and isset($row[$key])) ? $row[$key] : $a;
                    $_rows[$key2] = $row;
                    ++$a;
                }
                $result->closeCursor();
                $this->_Cache->set($cache_key, $_rows, $ttl);
            }
        }

        return $_rows;
    }
}
