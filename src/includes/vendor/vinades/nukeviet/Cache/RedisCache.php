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
use Redis;

/**
 * NukeViet\Cache\RedisCache
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class RedisCache extends Cache
{
    /**
     * @var string Khóa cache phiên bản cache theo module[lang]
     */
    private const VERSION_KEY = '_sys:version';

    /**
     * @var Redis
     */
    private Redis $redis;

    /**
     * @var array Phiên bản cache
     */
    private array $cacheVersion;

    /**
     * @param string $host
     * @param int $port
     * @param float $timeout
     * @param string $password
     * @param int $dbIndex
     * @param string $lang
     * @param string $cachePrefix
     * @param string $keySuffix
     */
    public function __construct(string $host, int $port, float $timeout, string $password, int $dbIndex, string $lang, string $cachePrefix, string $keySuffix)
    {
        parent::__construct($lang, $cachePrefix, $keySuffix);

        $redis = new Redis();

        $connected = false;
        if ($redis->pconnect($host, $port, $timeout) === true) {
            $connected = true;
        } elseif ($redis->connect($host, $port, $timeout) === true) {
            $connected = true;
        }
        if ($connected !== true) {
            throw new \RuntimeException('Can not connect to Redis server!');
        }

        if (!empty($password) and $redis->auth($password) !== true) {
            throw new \RuntimeException('Can not Authenticate Redis server!');
        }

        if ($redis->select($dbIndex) !== true) {
            throw new \RuntimeException('Can not connect to Redis DB!');
        }

        $checkOptions = [];
        if (!empty($cachePrefix)) {
            $checkOptions[] = $redis->setOption(Redis::OPT_PREFIX, $cachePrefix);
        }
        $checkOptions[] = $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        foreach ($checkOptions as $opt) {
            if ($opt !== true) {
                throw new \RuntimeException('Can not set Redis option!');
            }
        }

        $this->redis = $redis;
        $this->cacheVersion = (array) ($this->redis->get(self::VERSION_KEY) ?: []);
    }

    /**
     * @return void
     */
    private function updateVersion(): void
    {
        $this->redis->set(self::VERSION_KEY, $this->cacheVersion);
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
        // Note: $ttl not check in Redis cache
        $key = $this->key($moduleName, $fileName, $lang);
        return $this->redis->get($key);
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
        $this->set($key, $content, $ttl);
        return true;
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
        if ($this->redis->exists($key)) {
            $this->redis->del($key);
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

        $keyCache = $this->key($moduleName, $sql . '_' . $this->keySuffix, $lang);

        $list = $this->redis->get($keyCache);
        if ($list) {
            return $list;
        }

        $list = parent::getList($sql, $key);
        if ($list === false) {
            return [];
        }

        $this->set($keyCache, $list, $ttl);

        return $list;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed $ttl
     * @return void
     */
    private function set($key, $value, $ttl = 0)
    {
        $this->redis->set($key, $value);
        if ($ttl > 0) {
            $this->redis->expire($key, $ttl);
        }
    }

    /**
     * @return Redis
     */
    public function instance(): mixed
    {
        return $this->redis;
    }
}
