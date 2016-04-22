<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC.
 * All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

namespace NukeViet\Cache;

use Memcached;

class Memcacheds
{

    private $_Lang = 'vi';

    private $_Cache_Prefix = '';

    private $_Db;

    private $_Cache;

    public function __construct($Host, $Port, $Lang, $Cache_Prefix)
    {
        $this->_Lang = $Lang;
        $this->_Cache_Prefix = $Cache_Prefix;
        $this->_Cache = new Memcached();
        $this->_Cache->addServer($Host, $Post);
    }

    /**
     *
     * @param mixed $sys
     *
     * @return
     *
     */
    public function delAll($sys = true)
    {
        $this->_Cache->flush();
    }

    /**
     *
     * @param mixed $module_name
     * @param mixed $lang
     *
     * @return void
     */
    public function delMod($module_name, $lang = '')
    {
        $AllKeys = $this->_Cache->getAllKeys();
        foreach ($AllKeys as $_key) {
            if (preg_match('/^' . $module_name . '\_/', $_key)) {
                $this->_Cache->delete($_key);
            }
        }
    }

    /**
     *
     * @param mixed $module_name
     * @param mixed $filename
     * @return
     *
     */
    public function getItem($module_name, $filename)
    {
        return $this->_Cache->get($module_name . '_' . md5($filename));
    }

    /**
     *
     * @param mixed $module_name
     * @param mixed $filename
     * @param mixed $content
     * @return
     *
     */
    public function setItem($module_name, $filename, $content)
    {
        return $this->_Cache->set($module_name . '_' . md5($filename), $content);
    }

    /**
     *
     * @param resource $db
     */
    public function setDb($db)
    {
        $this->_Db = $db;
    }

    /**
     *
     * @param mixed $sql
     * @param mixed $key
     * @param mixed $modname
     * @param mixed $lang
     * @return
     *
     */
    public function db($sql, $key, $modname, $lang = '')
    {
        $_rows = array();

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
                $this->_Cache->set($cache_key, $_rows);
            }
        }

        return $_rows;
    }
}
