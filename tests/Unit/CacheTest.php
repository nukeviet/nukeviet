<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Memcached;
use NukeViet\Cache\MemcachedCache;
use NukeViet\Cache\RedisCache;
use Redis;
use Tests\Support\UnitTester;

class CacheTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
        global $db;
        $db->query("DROP TABLE IF EXISTS abcd");
    }

    /**
     * @return void
     */
    private function prepareTableForCacheTest(): void
    {
        global $db;

        $db->query("DROP TABLE IF EXISTS abcd");
        $db->query("CREATE TABLE IF NOT EXISTS abcd (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL DEFAULT '',
            PRIMARY KEY (id)
        ) ENGINE=InnoDB");
        $db->query("INSERT INTO abcd (title) VALUES ('Title 1'), ('Title 2'), ('Title 3')");
    }

    /**
     * @group install
     * @group all
     * @group cache
     */
    public function testFilesCache()
    {
        global $db_config, $db;

        $cache = \NukeViet\Cache::newInstance([
            'cached' => 'files'
        ]);
        $cache->setDb($db);

        // Xóa hết cache thì không còn tệp cache nào tồn tại
        $cache->delAll(true);
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache', ext: 'cache');
        $this->assertEmpty($files, 'No cache files should exist initially');

        // Kiểm tra cache SQL
        $sql = "SELECT * FROM " . $db_config['prefix'] . "_authors";
        $cache->db($sql, 'admin_id', 'news');
        $cache->db($sql, 'admin_id', 'news'); // Gọi lại lần 2 để kiểm tra cache không tạo thêm tệp

        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache/news', ext: 'cache');
        $this->assertCount(1, $files, 'One cache file should be created after caching SQL query');

        $cache->db($sql, 'admin_id', 'news', 'en');
        $cache->db($sql, 'admin_id', 'news', 'en'); // Gọi lại lần 2 để kiểm tra cache không tạo thêm tệp
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache/news', ext: 'cache');
        $this->assertCount(2, $files, 'Two cache files should exist after caching SQL query with different language');

        // Đưa 2 tệp đó về nội dung [] rồi lấy lại cache để đảm bảo lấy đúng từ tệp đã tạo
        foreach ($files as $file) {
            file_put_contents(NV_ROOTDIR . '/data/cache/news/' . $file, serialize([]), LOCK_EX);
        }
        $content = $cache->db($sql, 'admin_id', 'news');
        $this->assertEmpty($content, 'Cached content should be empty array after overwriting cache files');

        // Xóa hết cache
        $cache->delAll(true);
        // Tạo lại cache db có hạn sử dụng, hết hạn sau 1 giây
        $cache->db($sql, 'admin_id', 'news', 'en', 1);
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache/news', ext: 'cache');
        foreach ($files as $file) {
            file_put_contents(NV_ROOTDIR . '/data/cache/news/' . $file, serialize([]), LOCK_EX);
        }
        // Đọc lại cache nó phải là rỗng
        $content = $cache->db($sql, 'admin_id', 'news', 'en', 1);
        $this->assertEmpty($content, 'Cached content should be empty array after overwriting cache files with expiration');
        // Đưa tệp cache về trước thời điểm hiện tại 5 phút để đảm bảo hết hạn
        foreach ($files as $file) {
            touch(NV_ROOTDIR . '/data/cache/news/' . $file, time() - 300);
        }
        clearstatcache();
        // Đọc lại cache nó phải là lấy lại từ CSDL
        $content = $cache->db($sql, 'admin_id', 'news', 'en', 1);
        $this->assertNotEmpty($content, 'Cached content should be retrieved from database after cache expiration');

        $cache->delAll(true);

        /**
         * Đặt cache của 2 module sau đó xóa module này thì không được xóa module kia
         */
        $cache->db($sql, 'admin_id', 'news');
        $cache->db($sql, 'admin_id', 'news', 'en');
        $cache->db($sql, 'admin_id', 'page');
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache', ext: 'cache');
        $this->assertCount(3, $files, 'Two cache files should exist for different modules');
        $cache->delMod('news', 'en');
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache', ext: 'cache');
        $this->assertCount(2, $files, 'One cache file should remain after deleting news module cache with en language');
        $cache->delMod('news');
        $files = $this->tester->listFile(NV_ROOTDIR . '/data/cache', ext: 'cache');
        $this->assertCount(1, $files, 'One cache file should remain after deleting news module cache');

        $cache->delAll(true);

        /**
         * Kiểm tra setItem, getItem, delItem
         * cho trường hợp có và không có TTL
         */
        $content = 'test content';
        $cache_name = 'test_cache.cache';
        $cache->setItem('news', $cache_name, $content);
        touch(NV_ROOTDIR . '/data/cache/news/vi_' . $cache_name, time() - 300);
        clearstatcache();

        $check = $cache->getItem('news', $cache_name);
        $this->assertEquals($content, $check, 'Cache content should match the set content without TTL');

        // Phải trả về false do đã hết hạn
        $check = $cache->getItem('news', $cache_name, ttl: 10);
        $this->assertFalse($check, 'Cache retrieval should return false after expiration with TTL');

        // Xóa xong tệp không tồn tại nữa
        $cache->delItem('news', $cache_name);
        $file = NV_ROOTDIR . '/data/cache/news/' . $cache_name;
        $this->assertFileDoesNotExist($file, 'Cache file should not exist after deletion');
    }

    /**
     * @param \NukeViet\Cache\MemcachedCache|\NukeViet\Cache\RedisCache $cache
     * @param \NukeViet\Cache\MemcachedCache|\NukeViet\Cache\RedisCache $cacheOther
     * @return void
     */
    private function testRedisMemcachedCommon(MemcachedCache|RedisCache $cache, MemcachedCache|RedisCache $cacheOther): void
    {
        global $db_config, $db;

        $sql = "SELECT * FROM " . $db_config['prefix'] . "_authors";

        /**
         * Bài test thứ nhất: Ghi cache từ 2 server, xóa hết cache server 1
         * Cache server 2 vẫn còn không được phép xóa sạch hệ thống
         */
        $content = 'test content';
        $cache_name = 'test_cache.cache';
        $cache->setItem('news', $cache_name, $content);
        $check = $cache->getItem('news', $cache_name);
        $this->assertEquals($content, $check, 'Cache content should match the set content');

        $cacheOther->setItem('news', $cache_name, $content);
        $check = $cacheOther->getItem('news', $cache_name);
        $this->assertEquals($content, $check, 'Cache content should match the set content');

        $cache->delAll(true);
        $check = $cacheOther->getItem('news', $cache_name);
        $this->assertEquals($content, $check, 'Cache on server 2 should remain after deleting all cache on server 1');

        /**
         * Bài test thứ hai: Xóa toàn bộ cache của server theo ngôn ngữ
         */
        $modules = ['news', 'page'];
        $langs = ['vi', 'en', 'fr'];
        foreach ($modules as $module) {
            foreach ($langs as $lang) {
                $cache->setItem($module, $cache_name, $content, $lang);
            }
        }
        // Xóa hết theo lang vi
        $cache->delAll(false);
        // Kiểm tra các ngôn ngữ khác vẫn còn
        foreach ($modules as $module) {
            foreach (array_slice($langs, 1) as $lang) {
                $check = $cache->getItem($module, $cache_name, $lang);
                $this->assertEquals($content, $check, "Cache for module $module and lang $lang should remain after deleting 'vi' language cache");
            }
        }
        // Xóa hết cả site thì không còn cache nào
        $cache->delAll(true);
        foreach ($modules as $module) {
            foreach ($langs as $lang) {
                $check = $cache->getItem($module, $cache_name, $lang);
                $this->assertFalse($check, "Cache for module $module and lang $lang should be deleted after deleting all cache");
            }
        }

        /**
         * Bài test cache SQL
         */
        global $db;
        $this->prepareTableForCacheTest();

        $sql = "SELECT * FROM abcd";
        $check = $cache->db($sql, 'id', 'news');
        $this->assertCount(3, $check, 'Database cache should return 3 rows initially');
        // Thêm 1 row thì cache vẫn phải trả về 3 row
        $db->query("INSERT INTO abcd (title) VALUES ('Title 4')");
        $check = $cache->db($sql, 'id', 'news');
        $this->assertCount(3, $check, 'Database cache should still return 3 rows after inserting new row without cache expiration');
        // Xóa cache, đọc lại phải ra 4 row
        $cache->delMod('news');
        $check = $cache->db($sql, 'id', 'news');
        $this->assertCount(4, $check, 'Database cache should return 4 rows after cache deletion');

        // Cache với TTL 1 giây thì 1 giây sau phải hết hạn
        $cache->delMod('news');
        $cache->db($sql, 'id', 'news', '', 1);
        $db->query("INSERT INTO abcd (title) VALUES ('Title 5')");
        sleep(2);
        $check = $cache->db($sql, 'id', 'news', '', 1);
        $this->assertCount(5, $check, 'Database cache should return 5 rows after cache expiration with TTL. Current count: ' . count($check));

        /**
         * Bài test với setItem, getItem, delItem không TTL
         */
        $cache->delAll(true);

        $content = 'test content';
        $cache_name = 'test_cache.cache';
        // Set xong get lại phải đúng
        $cache->setItem('news', $cache_name, $content);
        $check = $cache->getItem('news', $cache_name);
        $this->assertEquals($content, $check, 'Cache content should match the set content without TTL');
        // Xóa xong get lại phải false
        $cache->delItem('news', $cache_name);
        $check = $cache->getItem('news', $cache_name);
        $this->assertFalse($check, 'Cache retrieval should return false after deletion without TTL');

        /**
         * Bài test với setItem, getItem, delItem có TTL
         */
        $cache->delAll(true);
        // Set hiệu lực 1s dừng 2s xong get lại phải false
        $cache->setItem('news', $cache_name, $content, '', 1);
        sleep(2);
        $check = $cache->getItem('news', $cache_name, '', 1);
        $this->assertFalse($check, 'Cache retrieval should return false after expiration with TTL');
    }

    /**
     * @group install
     * @group all
     * @group cache
     */
    public function testMemcachedCache()
    {
        global $db;

        if (empty($_ENV['MEMCACHED_HOST'])) {
            return;
        }
        $config = [
            'memcached_host' => $_ENV['MEMCACHED_HOST'],
            'memcached_port' => !empty($_ENV['MEMCACHED_PORT']) ? (int) $_ENV['MEMCACHED_PORT'] : 11211,
            'cached' => 'memcached',
            'cache_prefix' => 'server1',
        ];
        $cache = \NukeViet\Cache::newInstance($config);
        $cache->setDb($db);

        /**
         * @var Memcached
         */
        $cacheMem = $cache->instance();

        // Tạo cache khác mô phỏng server khác
        $configOther = $config;
        $configOther['cache_prefix'] = 'server2';
        $cacheOther = \NukeViet\Cache::newInstance($configOther);
        $cacheOther->setDb($db);

        /**
         * @var Memcached
         */
        $cacheMemOther = $cacheOther->instance();

        // Dọn sạch 2 server
        $cacheMem->flush();
        $cacheMemOther->flush();

        // Run common tests
        $this->testRedisMemcachedCommon($cache, $cacheOther);
    }

    /**
     * @group install
     * @group all
     * @group cache
     */
    public function testRedisCache()
    {
        global $db;

        if (empty($_ENV['REDIS_HOST'])) {
            return;
        }
        $config = [
            'redis_host' => $_ENV['REDIS_HOST'],
            'redis_port' => !empty($_ENV['REDIS_PORT']) ? (int) $_ENV['REDIS_PORT'] : 6379,
            'cached' => 'redis',
            'redis_timeout' => $_ENV['REDIS_TIMEOUT'] ?? 2.5,
            'redis_password' => $_ENV['REDIS_PASS'] ?? '',
            'redis_db_index' => $_ENV['REDIS_DB'] ?? 0,
            'cache_prefix' => 'server1',
        ];
        $cache = \NukeViet\Cache::newInstance($config);
        $cache->setDb($db);

        /**
         * @var Redis
         */
        $cacheRedis = $cache->instance();

        // Tạo cache khác mô phỏng server khác
        $configOther = $config;
        $configOther['cache_prefix'] = 'server2';
        $cacheOther = \NukeViet\Cache::newInstance($configOther);
        $cacheOther->setDb($db);

        /**
         * @var Redis
         */
        $cacheRedisOther = $cacheOther->instance();

        // Dọn sạch 2 server
        $cacheRedis->flushDB();
        $cacheRedisOther->flushDB();

        // Run common tests
        $this->testRedisMemcachedCommon($cache, $cacheOther);
    }
}
