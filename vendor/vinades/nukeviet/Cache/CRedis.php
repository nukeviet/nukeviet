<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

namespace NukeViet\Cache;

use Redis;
use RedisException;

/**
 * CRedis
 * 
 * @package NukeViet Cache
 * @author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @version 4.0
 * @access public
 */
class CRedis
{

    private $_Lang = 'vi';

    private $_Cache_Prefix = '';

    private $_Db;

    private $_Cache;

    /**
     * CRedis::__construct()
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
        
        try {
            $redis = new Redis();
        } catch (RedisException $e) {
            trigger_error("Can not find Redis server!", 256);
        }
        
        if ($redis->connect($Host, $Port, $Timeout) !== true) {
            trigger_error("Can not connect to Redis server!", 256);
        }
        
        if (!empty($Password) and $redis->auth($Password) !== true) {
            trigger_error("Can not Authenticate Redis server!", 256);
        }
        
        if ($redis->select($DBnumber) !== true) {
            trigger_error("Can not connect to Redis DB!", 256);
        }
        
        $checkOptions = array();
        $checkOptions[] = $redis->setOption(Redis::OPT_PREFIX, $Cache_Prefix);
        $checkOptions[] = $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        
        foreach ($checkOptions as $opt) {
            if ($opt !== true) {
                trigger_error("Can not set Redis option!", 256);
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
