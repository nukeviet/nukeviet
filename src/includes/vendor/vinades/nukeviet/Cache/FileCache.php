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

use NukeViet\Cache;

/**
 * NukeViet\Cache\FileCache
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class FileCache extends Cache
{
    /**
     * @var string
     */
    private $cacheDir = '/tmp';

    /**
     * @var int
     */
    private $currentTime = 0;

    /**
     * @var int
     */
    private $expiredFiles = 0;

    /**
     * @param string $cacheDir
     * @param string $lang
     * @param string $cachePrefix
     * @param string $keySuffix
     */
    public function __construct(string $cacheDir, string $lang, string $cachePrefix, string $keySuffix)
    {
        parent::__construct($lang, $cachePrefix, $keySuffix);
        $this->cacheDir = $cacheDir;

        if (defined('NV_CURRENTTIME')) {
            $this->currentTime = NV_CURRENTTIME;
        } else {
            $this->currentTime = time();
        }
    }

    /**
     * @param string $moduleName
     * @param string $pattern
     * @return void
     */
    private function delete(string $moduleName, string $pattern): void
    {
        $dir = $this->cacheDir . '/' . $moduleName;

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
     * @param bool $sys
     * @return void
     */
    public function delAll(bool $sys = true): void
    {
        if ($dh = opendir($this->cacheDir)) {
            if ($sys) {
                $pattern = '/(.*)\.cache$/';
            } else {
                $pattern = '/^' . $this->lang . '\_(.*)\.cache$/';
            }

            while (($modname = readdir($dh)) !== false) {
                if (preg_match('/^([a-zA-Z0-9\_\-]+)$/', $modname)) {
                    $this->delete($modname, $pattern);
                }
            }
            closedir($dh);
        }
    }

    /**
     * @param string $moduleName
     * @param string $lang
     * @return void
     */
    public function delMod(string $moduleName, string $lang = ''): void
    {
        if (!empty($lang)) {
            $pattern = '/^' . $lang . '\_(.*)\.cache$/';
        } else {
            $pattern = '/(.*)\.cache$/';
        }

        $this->delete($moduleName, $pattern);
    }

    /**
     * @param string $moduleName
     * @param string $fileName
     * @param string $lang
     * @param int $ttl
     * @return false|string
     */
    public function getItem(string $moduleName, string $fileName, string $lang = '', int $ttl = 0): false|string
    {
        if (!preg_match('/^([a-zA-Z0-9\_\-]+)\.cache/', $fileName)) {
            return false;
        }

        $fullname = $this->cacheDir . '/' . $moduleName . '/' . ($lang ?: $this->lang) . '_' . strtolower($fileName);

        if (!is_file($fullname)) {
            return false;
        }

        if ($ttl > 0) {
            $ttl += rand(1, 10);
        }

        if ($ttl > 0 and (($this->currentTime - filemtime($fullname)) > $ttl) and $this->expiredFiles < 5) {
            ++$this->expiredFiles;

            return false;
        }

        return file_get_contents($fullname);
    }

    /**
     * @param string $moduleName
     * @param string $fileName
     * @param string $content
     * @param string $lang
     * @param int $ttl
     * @return bool|int
     */
    public function setItem(string $moduleName, string $fileName, string $content, string $lang = '', int $ttl = 0): bool|int
    {
        if (!preg_match('/^([a-zA-Z0-9\_\-]+)\.cache/', $fileName)) {
            return false;
        }

        if (!is_dir($this->cacheDir . '/' . $moduleName)) {
            mkdir($this->cacheDir . '/' . $moduleName, 0777, true);
        }

        return file_put_contents($this->cacheDir . '/' . $moduleName . '/' . ($lang ?: $this->lang) . '_' . strtolower($fileName), $content);
    }

    /**
     * @param string $moduleName
     * @param string $fileName
     * @param string $lang
     * @return bool
     */
    public function delItem(string $moduleName, string $fileName, string $lang = ''): bool
    {
        $fullname = $this->cacheDir . '/' . $moduleName . '/' . ($lang ?: $this->lang) . '_' . strtolower($fileName);

        if (is_file($fullname) && unlink($fullname)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sql
     * @param string $key
     * @param string $moduleName
     * @param string $lang
     * @param int $ttl
     * @return array
     */
    public function db(string $sql, string $key, string $moduleName, string $lang = '', int $ttl = 0): array
    {
        if (empty($sql)) {
            return [];
        }

        $cache_file = md5($sql) . '_' . $this->keySuffix . '.cache';

        if (($cache = $this->getItem($moduleName, $cache_file, $lang, $ttl)) !== false) {
            $data = unserialize($cache);
            return is_array($data) ? $data : [];
        }

        $list = parent::getList($sql, $key);
        if ($list === false) {
            return [];
        }

        $this->setItem($moduleName, $cache_file, serialize($list), $lang, $ttl);

        return $list;
    }

    /**
     * @return null
     */
    public function instance(): mixed
    {
        return null;
    }
}
