<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

namespace NukeViet\Cache;

use Redis as CRedis;

/**
 * Redis
 *
 * @package NukeViet Cache
 * @author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @version 4.0
 * @access public
 */
class Redis
{

    private $_Lang = 'vi';

    private $_Cache_Prefix = '';

    private $_Db;

    private $_Cache;

    /**
     * Redis::__construct()
     *
     * @param mixed $Host
     * @param mixed $Port
     * @param mixed $Timeout
     * @param mixed $Password
     * @param mixed $DBnumber
     * @param mixed $Lang
     * @param mixed $Cache_Prefix
     * @return void
     */
    public function __construct($Host, $Port, $Timeout, $Password, $DBnumber, $Lang, $Cache_Prefix)
    {
        $this->_Lang = $Lang;
        $this->_Cache_Prefix = $Cache_Prefix;

        $redis = new CRedis();

        $connected = false;
        if ($redis->pconnect($Host, $Port, $Timeout) === true) {
            $connected = true;
        } elseif ($redis->connect($Host, $Port, $Timeout) === true) {
            $connected = true;
        }
        if ($connected !== true) {
            trigger_error('Can not connect to Redis server!', 256);
        }

        if (!empty($Password) and $redis->auth($Password) !== true) {
            trigger_error('Can not Authenticate Redis server!', 256);
        }

        if ($redis->select($DBnumber) !== true) {
            trigger_error('Can not connect to Redis DB!', 256);
        }

        $checkOptions = array();
        $checkOptions[] = $redis->setOption(Redis::OPT_PREFIX, $Cache_Prefix);
        $checkOptions[] = $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        foreach ($checkOptions as $opt) {
            if ($opt !== true) {
                trigger_error('Can not set Redis option!', 256);
            }
        }

        $this->_Cache = $redis;
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
        $this->_Cache->flushDb();
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
        $AllKeys = $this->_Cache->keys(str_replace('-', '\-', $module_name) . '*');

        foreach ($AllKeys as $key) {
            $this->_Cache->delete(substr($key, strlen($this->_Cache_Prefix)));
        }
    }

    /**
     * Redis::getItem()
     *
     * @param mixed $module_name
     * @param mixed $filename
     * @param integer $ttl
     * @return
     */
    public function getItem($module_name, $filename, $ttl = 0)
    {
        // Note: $ttl not check in Redis cache
        return $this->_Cache->get($module_name . '_' . md5($filename));
    }

    /**
     * Redis::setItem()
     *
     * @param mixed $module_name
     * @param mixed $filename
     * @param mixed $content
     * @param integer $ttl
     * @return
     */
    public function setItem($module_name, $filename, $content, $ttl = 0)
    {
        return $this->set($module_name . '_' . md5($filename), $content, $ttl);
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
     * Redis::db()
     *
     * @param mixed $sql
     * @param mixed $key
     * @param mixed $modname
     * @param string $lang
     * @param integer $ttl
     * @return
     */
    public function db($sql, $key, $modname, $lang = '', $ttl = 0)
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
                $this->set($cache_key, $_rows, $ttl);
            }
        }

        return $_rows;
    }

    /**
     * Redis::set()
     *
     * @param mixed $key
     * @param mixed $value
     * @param integer $ttl
     * @return void
     */
    private function set($key, $value, $ttl = 0)
    {
        $this->_Cache->set($key, $value);

        if ($ttl > 0) {
            $this->_Cache->setTimeout($key, $ttl);
        }
    }
}
