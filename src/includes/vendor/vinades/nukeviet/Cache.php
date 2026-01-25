<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet;

use Memcached;
use Redis;

/**
 * NukeViet\Cache
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
abstract class Cache
{
    /**
     * @var array<string, Cache\FileCache|Cache\MemcachedCache|Cache\RedisCache>
     */
    protected static array $instances = [];

    /**
     * @var string
     */
    protected $lang = 'vi';

    /**
     * Tiền tố đứng trước mọi key
     *
     * @var string
     */
    protected $cachePrefix = '';

    /**
     * Tiền tố phụ
     *
     * @var string
     */
    protected $keySuffix = '';

    /**
     * @var \NukeViet\Core\Database
     */
    protected $db;

    /**
     * @param string $lang
     * @param string $cachePrefix
     */
    public function __construct(string $lang, string $cachePrefix, string $keySuffix)
    {
        $this->lang = $lang;
        $this->cachePrefix = $cachePrefix;
        $this->keySuffix = $keySuffix;
    }

    /**
     * @param \NukeViet\Core\Database $db
     * @return Cache
     */
    public function setDb($db): Cache
    {
        $this->db = $db;
        return $this;
    }

    /**
     * @param array $global_config
     * @return Cache\FileCache|Cache\MemcachedCache|Cache\RedisCache|null
     */
    public static function getInstance(array $global_config = [])
    {
        $driver = $global_config['cached'] ?? 'files';

        if (!isset(self::$instances[$driver])) {
            self::$instances[$driver] = self::createDriver($global_config);
        }

        return self::$instances[$driver];
    }

    /**
     * @param array $global_config
     * @return Cache\FileCache|Cache\MemcachedCache|Cache\RedisCache|null
     */
    public static function newInstance(array $global_config = [])
    {
        return self::createDriver($global_config);
    }

    /**
     * Kiểm tra kết nối đến hệ thống cache, trả về chuỗi 'success' nếu thành công, ngược lại trả về thông báo lỗi
     *
     * @param array $global_config
     * @return string
     */
    public static function testInstance(array $global_config = []): string
    {
        $driver = $global_config['cached'] ?? 'files';
        if ($driver === 'files') {
            return 'success';
        }
        try {
            $cache = self::createDriver($global_config);
        } catch (\RuntimeException $e) {
            return $e->getMessage();
        }

        if ($driver === 'memcached') {
            /**
             * @var Memcached
             */
            $instance = $cache->instance();
            $check = $instance->getVersion();
            if ($check === false or empty($check)) {
                return 'Could not connect to Memcached server.';
            }
            return 'success';
        }

        if ($driver === 'redis') {
            /**
             * @var Redis
             */
            $instance = $cache->instance();
            $check = $instance->ping();
            if ($check !== true) {
                return 'Could not connect to Redis server.';
            }
            return 'success';
        }

        return 'Driver ' . $driver . ' is not supported.';
    }

    /**
     * @param array $global_config
     * @throws \RuntimeException
     * @return Cache\FileCache|Cache\MemcachedCache|Cache\RedisCache
     */
    private static function createDriver(array $global_config)
    {
        foreach (['NV_LANG_DATA', 'NV_CACHE_PREFIX', 'NV_ROOTDIR', 'NV_CACHEDIR'] as $const) {
            if (!defined($const)) {
                throw new \RuntimeException($const . ' is not defined.');
            }
        }

        $driver = $global_config['cached'] ?? 'files';
        $cachePrefix = $global_config['cache_prefix'] ?? '';
        if (!empty($global_config['idsite'])) {
            $cachePrefix .= 's' . $global_config['idsite'] . '_';
        }

        // Sử dụng file hệ thống
        if ($driver === 'files') {
            return new Cache\FileCache(NV_ROOTDIR . '/' . NV_CACHEDIR, NV_LANG_DATA, $cachePrefix, NV_CACHE_PREFIX);
        }

        // Sử dụng Memcached
        if ($driver === 'memcached') {
            $host = $global_config['memcached_host'] ?? '';
            $port = $global_config['memcached_port'] ?? 11211;
            if (empty($host)) {
                throw new \RuntimeException('Memcached host is not defined.');
            }
            return new Cache\MemcachedCache($host, $port, NV_LANG_DATA, $cachePrefix, NV_CACHE_PREFIX);
        }

        // Sử dụng Redis
        if ($driver === 'redis') {
            $host = $global_config['redis_host'] ?? '';
            $port = $global_config['redis_port'] ?? 6379;
            $timeout = $global_config['redis_timeout'] ?? 2.5;
            $password = $global_config['redis_password'] ?? '';
            $db_index = $global_config['redis_db_index'] ?? 0;
            if (empty($host)) {
                throw new \RuntimeException('Redis host is not defined.');
            }
            return new Cache\RedisCache($host, $port, $timeout, $password, $db_index, NV_LANG_DATA, $cachePrefix, NV_CACHE_PREFIX);
        }

        throw new \RuntimeException('Unsupported cache driver: ' . $driver);
    }

    /**
     * @param string $sql
     * @param string $key
     * @return array|false
     */
    protected function getList($sql, $key): array|false
    {
        $list = false;

        if (($result = $this->db->query($sql)) !== false) {
            $list = [];
            $a = 0;
            while ($row = $result->fetch()) {
                $key2 = (!empty($key) and isset($row[$key])) ? $row[$key] : $a;
                $list[$key2] = $row;
                ++$a;
            }
            $result->closeCursor();
        }

        return $list;
    }

    /**
     * Xóa tất cả các mục trong bộ nhớ đệm
     *
     * @param bool $sys Đặt là true thì xóa toàn bộ các ngôn ngữ, false chỉ xóa ngôn ngữ hiện tại
     * @return void
     */
    abstract public function delAll(bool $sys = true): void;

    /**
     * Xóa tất cả các mục trong bộ nhớ đệm của một module
     *
     * @param string $moduleName Tên module cần xóa
     * @param string $lang Ngôn ngữ cần xóa, để trống thì xóa trên tất cả ngôn ngữ
     * @return void
     */
    abstract public function delMod(string $moduleName, string $lang = ''): void;

    /**
     * Đọc một mục từ bộ nhớ đệm
     *
     * @param string $moduleName Tên module
     * @param string $fileName Tên tệp cần đọc
     * @param string $lang Ngôn ngữ, để trống thì dùng ngôn ngữ hiện tại
     * @param int $ttl Thời gian sống của bộ nhớ đệm (tính bằng giây). Đặt là 0 để vô hạn. Bộ nhớ đệm dạng tệp mới kiểm tra ttl, Memcached và Redis thì ttl lúc tạo mục
     * @return void
     */
    abstract public function getItem(string $moduleName, string $fileName, string $lang = '', int $ttl = 0): false|string;

    /**
     * Ghi một mục vào bộ nhớ đệm
     *
     * @param string $moduleName Tên module
     * @param string $fileName Tên tệp cần ghi
     * @param string $content Nội dung cần ghi
     * @param string $lang Ngôn ngữ, để trống thì dùng ngôn ngữ hiện tại
     * @param int $ttl Thời gian sống của bộ nhớ đệm (tính bằng giây). Đặt là 0 để vô hạn. Đối với Memcached và Redis thì ttl dùng đặt thời gian sống của mục, còn tệp thì sẽ bỏ qua
     * @return bool|int Trả về true nếu thành công, false nếu thất bại.
     */
    abstract public function setItem(string $moduleName, string $fileName, string $content, string $lang = '', int $ttl = 0): bool|int;

    /**
     * Xóa một mục khỏi bộ nhớ đệm
     *
     * @param string $moduleName Tên module
     * @param string $fileName Tên tệp cần xóa
     * @param string $lang Ngôn ngữ, để trống thì dùng ngôn ngữ hiện tại
     * @return bool Trả về true nếu thành công, false nếu thất bại.
     */
    abstract public function delItem(string $moduleName, string $fileName, string $lang = ''): bool;

    /**
     * Lưu kết quả truy vấn cơ sở dữ liệu vào bộ nhớ đệm và trả về mảng kết quả
     *
     * @param string $sql Câu lệnh SQL cần thực thi
     * @param string $key Tên trường sẽ dùng làm khóa chính trong mảng kết quả
     * @param string $moduleName Tên module
     * @param string $lang Ngôn ngữ, để trống thì dùng ngôn ngữ hiện tại
     * @param int $ttl Thời gian sống của bộ nhớ đệm (tính bằng giây). Đặt là 0 để vô hạn.
     * @return array Mảng kết quả trả về từ truy vấn
     */
    abstract public function db(string $sql, string $key, string $moduleName, string $lang = '', int $ttl = 0): array;

    /**
     * Lấy instance tùy loại cache và có thể cấu hình thêm
     *
     * @return Memcached|Redis|null
     */
    abstract public function instance(): mixed;
}
