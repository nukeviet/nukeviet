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

use Memcached;
use NukeViet\Cache;

/**
 * NukeViet\Cache\MemcachedCache
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class MemcachedCache extends Cache
{
    /**
     * @var string Khóa cache phiên bản cache theo module[lang]
     */
    private const VERSION_KEY = '_sys:version';

    /**
     * @var Memcached
     */
    private Memcached $memcached;

    /**
     * @var array Phiên bản cache
     */
    private array $cacheVersion;

    /**
     * @param string $host
     * @param int $port
     * @param string $lang
     * @param string $cachePrefix
     * @param string $keySuffix
     */
    public function __construct(string $host, int $port, string $lang, string $cachePrefix, string $keySuffix)
    {
        parent::__construct($lang, $cachePrefix, $keySuffix);

        $this->memcached = new Memcached();
        $this->memcached->addServer($host, $port);

        if (!empty($cachePrefix)) {
            $this->memcached->setOption(Memcached::OPT_PREFIX_KEY, $cachePrefix);
        }

        /**
         * Memcached không hỗ trợ liệt kê tất cả các key nên chúng ta sẽ
         * quản lý phiên bản cache để khi xóa cache thì chỉ cần tăng phiên bản lên
         * là tất cả các cache cũ sẽ không còn giá trị sử dụng nữa (cache cũ sẽ được Memcached xóa tự động khi hết bộ nhớ được cấp phát).
         *
         * getAllKeys() không phải là phương án hợp lý bởi nó lấy hết trên mọi site, khi đó rất nặng
         * mặt khác nó không được hỗ trợ trên một số phiên bản Memcached nhất định cũng như không trả về đúng số keys tồn tại
         * tùy theo thời gian cache được ghi ra.
         */
        $this->cacheVersion = (array) ($this->memcached->get(self::VERSION_KEY) ?: []);
    }

    /**
     * @return void
     */
    private function updateVersion(): void
    {
        $this->memcached->set(self::VERSION_KEY, $this->cacheVersion);
    }

    /**
     * @param string $key
     * @param string $moduleName
     * @param string $lang
     * @return string
     */
    private function key(string $moduleName, string $key, string $lang = ''): string
    {
        $lang = $lang ?: $this->lang;

        if (!isset($this->cacheVersion[$moduleName], $this->cacheVersion[$moduleName][$lang])) {
            $this->cacheVersion[$moduleName][$lang] = 1;
            $this->updateVersion();
        }

        return 'v' . $this->cacheVersion[$moduleName][$lang] . '_' . $lang . '_' . $moduleName . '_' . md5(strtolower($key));
    }

    /**
     * @param bool $sys
     * @return void
     */
    public function delAll(bool $sys = true): void
    {
        $exist = 0;
        foreach ($this->cacheVersion as $module => $moduleLangs) {
            foreach ($moduleLangs as $lang => $version) {
                if ($sys or $lang == $this->lang) {
                    $exist++;
                    $this->cacheVersion[$module][$lang] = $version + 1;
                }
            }
        }

        $exist > 0 && $this->updateVersion();
    }

    /**
     * @param string $moduleName
     * @param string $lang
     * @return void
     */
    public function delMod(string $moduleName, string $lang = ''): void
    {
        // Chưa set cache của module này thì không có gì để xóa
        if (!isset($this->cacheVersion[$moduleName])) {
            return;
        }

        $exist = 0;
        foreach ($this->cacheVersion[$moduleName] as $langVersion => $version) {
            if (empty($lang) or $lang == $langVersion) {
                $exist++;
                $this->cacheVersion[$moduleName][$langVersion] = $version + 1;
            }
        }

        $exist > 0 && $this->updateVersion();
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
        // Note: $ttl not check in Memcached cache
        $key = $this->key($moduleName, $fileName, $lang);
        return $this->memcached->get($key);
    }

    /**
     * @param string $moduleName
     * @param string $fileName
     * @param string $content
     * @param string $lang
     * @param int $ttl
     * @return bool
     */
    public function setItem(string $moduleName, string $fileName, string $content, string $lang = '', int $ttl = 0): bool|int
    {
        $key = $this->key($moduleName, $fileName, $lang);
        return $this->memcached->set($key, $content, $ttl);
    }

    /**
     * @param string $moduleName
     * @param string $fileName
     * @param string $lang
     * @return bool
     */
    public function delItem(string $moduleName, string $fileName, string $lang = ''): bool
    {
        $key = $this->key($moduleName, $fileName, $lang);
        return $this->memcached->delete($key);
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

        $keyCache = $this->key($moduleName, $sql . '_' . $this->keySuffix, $lang);

        $list = $this->memcached->get($keyCache);
        if ($list) {
            return $list;
        }

        $list = parent::getList($sql, $key);
        if ($list === false) {
            return [];
        }

        $this->memcached->set($keyCache, $list, $ttl);

        return $list;
    }

    /**
     * @return Memcached
     */
    public function instance(): mixed
    {
        return $this->memcached;
    }
}
