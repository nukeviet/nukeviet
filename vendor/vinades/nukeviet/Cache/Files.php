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

/**
 * NukeViet\Cache\Files
 *
 * @package NukeViet\Cache
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Files
{
    private $_CacheDir = '/tmp';

    private $_Lang = 'vi';

    private $_Cache_Prefix = '';

    private $_Db;

    private $_Current_Time = 0;

    /**
     * __construct()
     *
     * @param string $CacheDir
     * @param string $Lang
     * @param string $Cache_Prefix
     */
    public function __construct($CacheDir, $Lang, $Cache_Prefix)
    {
        $this->_CacheDir = $CacheDir;
        $this->_Lang = $Lang;
        $this->_Cache_Prefix = $Cache_Prefix;

        if (defined('NV_CURRENTTIME')) {
            $this->_Current_Time = NV_CURRENTTIME;
        } else {
            $this->_Current_Time = time();
        }
    }

    /**
     * _delete()
     *
     * @param string $modname
     * @param string $pattern
     */
    private function _delete($modname, $pattern)
    {
        $dir = $this->_CacheDir . '/' . $modname;

        if (is_dir($dir) and $dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match($pattern, $file)) {
                    unlink($dir . '/' . $file);
                }
            }
            closedir($dh);
        }
    }

    /**
     * delAll()
     *
     * @param bool $sys
     */
    public function delAll($sys = true)
    {
        if ($dh = opendir($this->_CacheDir)) {
            if ($sys) {
                $pattern = '/(.*)\.cache$/';
            } else {
                $pattern = '/^' . $this->_Lang . '\_(.*)\.cache$/';
            }

            while (($modname = readdir($dh)) !== false) {
                if (preg_match('/^([a-z0-9\_\-]+)$/', $modname)) {
                    $this->_Delete($modname, $pattern);
                }
            }
            closedir($dh);
        }
    }

    /**
     * delMod()
     *
     * @param string $module_name
     * @param string $lang
     */
    public function delMod($module_name, $lang = '')
    {
        if (!empty($lang)) {
            $pattern = '/^' . $lang . '\_(.*)\.cache$/';
        } else {
            $pattern = '/(.*)\.cache$/';
        }

        $this->_Delete($module_name, $pattern);
    }

    /**
     * getItem()
     *
     * @param string $module_name
     * @param string $filename
     * @param int    $ttl
     * @return false|string
     */
    public function getItem($module_name, $filename, $ttl = 0)
    {
        if (!preg_match('/^([a-z0-9\_\-]+)\.cache/', $filename)) {
            return false;
        }

        $fullname = $this->_CacheDir . '/' . $module_name . '/' . $filename;

        if (!is_file($fullname)) {
            return false;
        }

        if ($ttl > 0 and ($this->_Current_Time - filemtime($fullname)) > $ttl) {
            return false;
        }

        return file_get_contents($fullname);
    }

    /**
     * setItem()
     *
     * @param string $module_name
     * @param string $filename
     * @param mixed  $content
     * @param int    $ttl
     * @return false|int
     */
    public function setItem($module_name, $filename, $content, $ttl = 0)
    {
        // Note: $ttl not use in Files cache
        if (!preg_match('/^([a-z0-9\_\-]+)\.cache/', $filename)) {
            return false;
        }

        if (!is_dir($this->_CacheDir . '/' . $module_name)) {
            mkdir($this->_CacheDir . '/' . $module_name, 0777, true);
        }

        return file_put_contents($this->_CacheDir . '/' . $module_name . '/' . $filename, $content);
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
     * @param mixed  $sql
     * @param string $key
     * @param string $modname
     * @param string $lang
     * @param int    $ttl
     * @return mixed
     */
    public function db($sql, $key, $modname, $lang = '', $ttl = 0)
    {
        // Note: $ttl not use in Files cache
        $list = [];

        if (empty($sql)) {
            return $list;
        }

        if (empty($lang)) {
            $lang = $this->_Lang;
        }

        $cache_file = $lang . '_' . md5($sql) . '_' . $this->_Cache_Prefix . '.cache';

        if (($cache = $this->getItem($modname, $cache_file)) != false) {
            $list = unserialize($cache);
        } else {
            if (($result = $this->_Db->query($sql)) !== false) {
                $a = 0;
                while ($row = $result->fetch()) {
                    $key2 = (!empty($key) and isset($row[$key])) ? $row[$key] : $a;
                    $list[$key2] = $row;
                    ++$a;
                }
                $result->closeCursor();

                $this->setItem($modname, $cache_file, serialize($list));
            }
        }

        return $list;
    }
}
