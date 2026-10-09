<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

/**
 * Kiểm tra cron cron_del_ip_logs(): dọn file log IP và bộ đếm đăng nhập sai theo tài khoản.
 *
 * Test gọi thẳng hàm cron trên bảng _users_login_attempts thật và thư mục data/logs/ip_logs thật:
 * - Các khóa test có tiền tố test_cron_, không trùng được với khóa thật (u + userid, n + md5)
 * - Bảng thật chưa có thì test tự tạo và xóa đi sau khi chạy, đã có thì giữ nguyên
 * - File log test có tên số âm, không trùng với file log IP thật
 *
 * Lưu ý: giống cron thật, lần chạy này cũng dọn các bản ghi và file log IP thật đã hết hạn.
 *
 * @group db
 */
class CronIpLogsDestroyTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    private const KEY_PREFIX = 'test_cron_';
    private const LOG_STALE = '-990001';
    private const LOG_FRESH = '-990002';

    /**
     * @var string
     */
    private $table;

    /**
     * Bảng thật do test tạo ra, cần xóa sau khi chạy
     *
     * @var bool
     */
    private $created_table = false;

    /**
     * @var array
     */
    private $config_backup = [];

    protected function _before()
    {
        global $db, $db_config, $global_config;

        if (empty($db) or empty($db->connect)) {
            $this->markTestSkipped('Chưa có kết nối CSDL');
        }

        if (!defined('NV_IS_CRON')) {
            define('NV_IS_CRON', true);
        }
        if (!defined('NV_USERS_GLOBALTABLE')) {
            define('NV_USERS_GLOBALTABLE', $db_config['prefix'] . '_users');
        }
        require_once NV_ROOTDIR . '/includes/cronjobs/ip_logs_destroy.php';

        $this->table = NV_USERS_GLOBALTABLE . '_login_attempts';
        if (!$db->query('SHOW TABLES LIKE ' . $db->quote($this->table))->fetchColumn()) {
            $db->exec('CREATE TABLE ' . $this->table . " (
                keyname varchar(64) NOT NULL,
                count smallint(5) unsigned NOT NULL DEFAULT '0',
                starttime int(11) unsigned NOT NULL DEFAULT '0',
                lasttime int(11) unsigned NOT NULL DEFAULT '0',
                PRIMARY KEY (keyname),
                KEY lasttime (lasttime)
            ) ENGINE=InnoDB");
            $this->created_table = true;
        }

        // Cấu hình cố định cho test: sai 5 lần trong 5 phút, cần captcha 30 phút
        foreach (['login_number_tracking', 'login_time_tracking', 'login_time_ban'] as $key) {
            $this->config_backup[$key] = $global_config[$key] ?? null;
        }
        $global_config['login_number_tracking'] = 5;
        $global_config['login_time_tracking'] = 5;
        $global_config['login_time_ban'] = 30;
    }

    protected function _after()
    {
        global $db, $global_config;

        foreach ($this->config_backup as $key => $value) {
            if ($value === null) {
                unset($global_config[$key]);
            } else {
                $global_config[$key] = $value;
            }
        }

        foreach ([self::LOG_STALE, self::LOG_FRESH] as $name) {
            $file = $this->logFile($name);
            if (file_exists($file)) {
                unlink($file);
            }
        }

        if (empty($this->table) or empty($db)) {
            return;
        }
        if ($this->created_table) {
            $db->exec('DROP TABLE IF EXISTS ' . $this->table);
        } else {
            $db->exec('DELETE FROM ' . $this->table . " WHERE keyname LIKE '" . self::KEY_PREFIX . "%'");
        }
    }

    /**
     * @param string $name
     * @return string
     */
    private function logFile(string $name): string
    {
        return NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs/' . $name . '.' . NV_LOGS_EXT;
    }

    /**
     * Thêm một bản ghi bộ đếm, $ago là số giây kể từ lần sai gần nhất
     *
     * @param string $key
     * @param int    $count
     * @param int    $ago
     */
    private function insertAttempt(string $key, int $count, int $ago): void
    {
        global $db;

        $time = NV_CURRENTTIME - $ago;
        $sth = $db->prepare('INSERT INTO ' . $this->table . ' (keyname, count, starttime, lasttime) VALUES (:keyname, :count, :starttime, :lasttime)');
        $sth->bindValue(':keyname', self::KEY_PREFIX . $key, \PDO::PARAM_STR);
        $sth->bindValue(':count', $count, \PDO::PARAM_INT);
        $sth->bindValue(':starttime', $time, \PDO::PARAM_INT);
        $sth->bindValue(':lasttime', $time, \PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * @param string $key
     * @return bool
     */
    private function attemptExists(string $key): bool
    {
        global $db;

        $sth = $db->prepare('SELECT COUNT(*) FROM ' . $this->table . ' WHERE keyname = :keyname');
        $sth->bindValue(':keyname', self::KEY_PREFIX . $key, \PDO::PARAM_STR);
        $sth->execute();

        return (bool) $sth->fetchColumn();
    }

    /**
     * Cron xóa bộ đếm đã hết hiệu lực ở cả hai trạng thái, giữ bộ đếm còn hiệu lực
     */
    public function testCleansExpiredLoginAttempts()
    {
        // Hết hiệu lực: lần sai cuối cách đây 2 giờ, quá max(5, 30) phút
        $this->insertAttempt('stale_required', 5, 7200);
        $this->insertAttempt('stale_counting', 2, 7200);

        // Còn hiệu lực
        $this->insertAttempt('live_required', 5, 60);
        $this->insertAttempt('live_counting', 1, 0);

        // Sát mốc: cách đúng 30 phút là hết hạn, kém 1 giây thì còn
        $this->insertAttempt('edge_expired', 5, 1800);
        $this->insertAttempt('edge_alive', 5, 1799);

        $this->assertTrue(cron_del_ip_logs());

        $this->assertFalse($this->attemptExists('stale_required'));
        $this->assertFalse($this->attemptExists('stale_counting'));
        $this->assertTrue($this->attemptExists('live_required'));
        $this->assertTrue($this->attemptExists('live_counting'));
        $this->assertFalse($this->attemptExists('edge_expired'));
        $this->assertTrue($this->attemptExists('edge_alive'));
    }

    /**
     * Tắt login_number_tracking thì cron vẫn dọn bộ đếm cũ còn sót lại
     */
    public function testCleansWhenTrackingDisabled()
    {
        global $global_config;

        $global_config['login_number_tracking'] = 0;
        $this->insertAttempt('stale_disabled', 5, 7200);
        $this->insertAttempt('live_disabled', 5, 60);

        $this->assertTrue(cron_del_ip_logs());

        $this->assertFalse($this->attemptExists('stale_disabled'));
        $this->assertTrue($this->attemptExists('live_disabled'));
    }

    /**
     * Phần dọn file log IP cũ vẫn hoạt động như trước
     */
    public function testStillCleansIpLogFiles()
    {
        $stale = $this->logFile(self::LOG_STALE);
        $fresh = $this->logFile(self::LOG_FRESH);
        file_put_contents($stale, '');
        file_put_contents($fresh, '');
        touch($stale, NV_CURRENTTIME - 7300);

        $this->assertTrue(cron_del_ip_logs());

        clearstatcache();
        $this->assertFileDoesNotExist($stale);
        $this->assertFileExists($fresh);
    }
}
