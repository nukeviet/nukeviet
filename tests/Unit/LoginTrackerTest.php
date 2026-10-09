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

use NukeViet\Core\Ips;
use NukeViet\Core\LoginTracker;
use Tests\Support\UnitTester;

/**
 * Kiểm tra bộ đếm đăng nhập sai theo tài khoản NukeViet\Core\LoginTracker.
 *
 * Test chạy trên một bảng tạm riêng, tạo ở _before và xóa ở _after, không đụng
 * vào bảng _users_login_attempts thật.
 *
 * @group db
 */
class LoginTrackerTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    /**
     * Cấu hình dùng cho test: sai 5 lần trong 5 phút thì cần captcha trong 30 phút
     */
    private const LIMIT = 5;
    private const WINDOW = 5;
    private const BAN = 30;

    /**
     * @var string
     */
    private $table;

    /**
     * Mốc thời gian gốc của mỗi test
     *
     * @var int
     */
    private $now;

    protected function _before()
    {
        global $db, $db_config;

        if (empty($db) or empty($db->connect)) {
            $this->markTestSkipped('Chưa có kết nối CSDL');
        }

        $this->table = $db_config['prefix'] . '_users_login_attempts_test';
        $this->now = NV_CURRENTTIME;

        $db->exec('DROP TABLE IF EXISTS ' . $this->table);
        $db->exec('CREATE TABLE ' . $this->table . " (
            keyname varchar(64) NOT NULL,
            count smallint(5) unsigned NOT NULL DEFAULT '0',
            starttime int(11) unsigned NOT NULL DEFAULT '0',
            lasttime int(11) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (keyname),
            KEY lasttime (lasttime)
        ) ENGINE=InnoDB");
    }

    protected function _after()
    {
        global $db;

        if (!empty($this->table) and !empty($db)) {
            $db->exec('DROP TABLE IF EXISTS ' . $this->table);
        }
    }

    /**
     * Tạo tracker với cấu hình test, cho phép đổi từng tham số
     *
     * @param int $limit
     * @param int $window
     * @param int $ban
     * @return LoginTracker
     */
    private function tracker(int $limit = self::LIMIT, int $window = self::WINDOW, int $ban = self::BAN): LoginTracker
    {
        global $db;

        return new LoginTracker($db, $this->table, $limit, $window, $ban);
    }

    /**
     * Ghi nhận $times lần sai, mỗi lần cách nhau $step giây kể từ $start
     *
     * @param LoginTracker $tracker
     * @param string       $key
     * @param int          $times
     * @param int          $start
     * @param int          $step
     * @return int Thời điểm của lần sai cuối cùng
     */
    private function failTimes(LoginTracker $tracker, string $key, int $times, int $start, int $step = 1): int
    {
        $time = $start;
        for ($i = 0; $i < $times; $i++) {
            $time = $start + $i * $step;
            $tracker->fail($key, $time);
        }

        return $time;
    }

    /**
     * Đọc bản ghi của một khóa trong bảng test
     *
     * @param string $key
     * @return array|false
     */
    private function row(string $key)
    {
        global $db;

        $sth = $db->prepare('SELECT * FROM ' . $this->table . ' WHERE keyname = :keyname');
        $sth->bindValue(':keyname', $key, \PDO::PARAM_STR);
        $sth->execute();

        return $sth->fetch();
    }

    /**
     * 1. Sai ít hơn ngưỡng thì chưa cần captcha
     */
    public function testBelowLimitNotRequired()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT - 1, $this->now);

        $this->assertFalse($tracker->isRequired('u1', $last));
        $this->assertSame(self::LIMIT - 1, (int) $this->row('u1')['count']);
    }

    /**
     * 2. Sai đủ ngưỡng thì cần captcha
     */
    public function testReachLimitRequired()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);

        $this->assertTrue($tracker->isRequired('u1', $last));
        $this->assertTrue($tracker->isRequired('u1', $last + self::BAN * 60 - 1));
    }

    /**
     * 3. Hai tài khoản đếm độc lập với nhau
     */
    public function testKeysAreIndependent()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);
        $this->failTimes($tracker, 'u2', 1, $this->now);

        $this->assertTrue($tracker->isRequired('u1', $last));
        $this->assertFalse($tracker->isRequired('u2', $last));
        $this->assertSame(1, (int) $this->row('u2')['count']);
    }

    /**
     * 4. Đổi IP giữa các lần sai không làm bộ đếm về 0, đây là lỗi gốc của Blocker
     */
    public function testCounterIndependentOfIp()
    {
        $backup = Ips::$my_ip2long;
        $tracker = $this->tracker();

        try {
            for ($i = 0; $i < self::LIMIT; $i++) {
                Ips::$my_ip2long = (string) (167772160 + $i * 65536);
                $tracker->fail('u1', $this->now + $i);
            }
        } finally {
            Ips::$my_ip2long = $backup;
        }

        $this->assertTrue($tracker->isRequired('u1', $this->now + self::LIMIT));
        $this->assertSame(self::LIMIT, (int) $this->row('u1')['count']);
    }

    /**
     * 5. Các lần sai cách nhau quá cửa sổ đếm thì đếm lại từ 1
     */
    public function testWindowExpiredRestartsCount()
    {
        $tracker = $this->tracker();
        $this->failTimes($tracker, 'u1', self::LIMIT - 1, $this->now);

        $later = $this->now + self::WINDOW * 60 + 1;
        $tracker->fail('u1', $later);

        $row = $this->row('u1');
        $this->assertSame(1, (int) $row['count']);
        $this->assertSame($later, (int) $row['starttime']);
        $this->assertFalse($tracker->isRequired('u1', $later));
    }

    /**
     * 5b. Sai đúng tại mốc cuối cửa sổ vẫn được cộng dồn
     */
    public function testWindowBoundaryKeepsCount()
    {
        $tracker = $this->tracker();
        $this->failTimes($tracker, 'u1', self::LIMIT - 1, $this->now, 0);

        $tracker->fail('u1', $this->now + self::WINDOW * 60);

        $this->assertSame(self::LIMIT, (int) $this->row('u1')['count']);
    }

    /**
     * 6. Đang cần captcha thì hết cửa sổ đếm vẫn còn cần captcha, sai tiếp không đếm lại
     */
    public function testRequiredStateSurvivesWindow()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);

        $later = $last + self::WINDOW * 60 + 60;
        $this->assertTrue($tracker->isRequired('u1', $later));

        $tracker->fail('u1', $later);
        $this->assertSame(self::LIMIT + 1, (int) $this->row('u1')['count']);
        $this->assertTrue($tracker->isRequired('u1', $later));
    }

    /**
     * 6b. Mỗi lần sai trong lúc cần captcha kéo dài thời gian cần captcha
     */
    public function testFailWhileRequiredExtendsBan()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);

        $again = $last + self::BAN * 60 - 10;
        $tracker->fail('u1', $again);

        $this->assertTrue($tracker->isRequired('u1', $last + self::BAN * 60 + 10));
        $this->assertFalse($tracker->isRequired('u1', $again + self::BAN * 60));
    }

    /**
     * 7. Hết thời gian cần captcha thì thôi, lần sai sau đó đếm lại từ 1
     */
    public function testBanExpired()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);

        $expired = $last + self::BAN * 60;
        $this->assertFalse($tracker->isRequired('u1', $expired));

        $tracker->fail('u1', $expired);
        $this->assertSame(1, (int) $this->row('u1')['count']);
        $this->assertFalse($tracker->isRequired('u1', $expired));
    }

    /**
     * 7b. login_time_ban = 0 thì thời gian cần captcha lấy bằng cửa sổ đếm, không bị tắt ngầm
     */
    public function testZeroBanFallsBackToWindow()
    {
        $this->assertSame(self::BAN * 60, $this->tracker()->getBanTime());

        $tracker = $this->tracker(self::LIMIT, self::WINDOW, 0);
        $this->assertSame(self::WINDOW * 60, $tracker->getBanTime());
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);

        $this->assertTrue($tracker->isRequired('u1', $last + self::WINDOW * 60 - 1));
        $this->assertFalse($tracker->isRequired('u1', $last + self::WINDOW * 60));
    }

    /**
     * 8. reset() xóa bộ đếm
     */
    public function testReset()
    {
        $tracker = $this->tracker();
        $last = $this->failTimes($tracker, 'u1', self::LIMIT, $this->now);
        $this->failTimes($tracker, 'u2', 2, $this->now);

        $tracker->reset('u1');

        $this->assertFalse($this->row('u1'));
        $this->assertFalse($tracker->isRequired('u1', $last));
        $this->assertNotFalse($this->row('u2'));
    }

    /**
     * 9. Khóa đếm: cùng userid thì cùng khóa, tên không tồn tại không phân biệt hoa thường và khoảng trắng
     */
    public function testGetKey()
    {
        // Đăng nhập bằng username hay email đều quy về userid
        $this->assertSame('u15', LoginTracker::getKey(15, 'admin'));
        $this->assertSame('u15', LoginTracker::getKey(15, 'admin@example.com'));

        // Tên không tồn tại
        $this->assertSame(LoginTracker::getKey(null, 'Ghost'), LoginTracker::getKey(null, '  ghost '));
        $this->assertSame(LoginTracker::getKey(0, 'ghost'), LoginTracker::getKey(null, 'ghost'));
        $this->assertSame(LoginTracker::getKey(null, 'Nguyễn'), LoginTracker::getKey(null, 'NGUYỄN'));
        $this->assertNotSame(LoginTracker::getKey(null, 'ghost'), LoginTracker::getKey(null, 'ghost2'));
        $this->assertMatchesRegularExpression('/^n[a-f0-9]{32}$/', LoginTracker::getKey(null, 'ghost'));

        // Tên không tồn tại không bao giờ trùng khóa của tài khoản thật
        $this->assertNotSame(LoginTracker::getKey(15, 'x'), LoginTracker::getKey(null, 'u15'));
    }

    /**
     * 9b. Khóa theo bước xác thực không trùng khóa tài khoản, mỗi bước một khóa riêng
     */
    public function testGetScopeKey()
    {
        $this->assertSame('tfa:15', LoginTracker::getScopeKey('tfa', 15));
        $this->assertSame('pwd:15', LoginTracker::getScopeKey('pwd', 15));
        $this->assertNotSame(LoginTracker::getKey(15, 'x'), LoginTracker::getScopeKey('tfa', 15));
        $this->assertNotSame(LoginTracker::getScopeKey('tfa', 15), LoginTracker::getScopeKey('pwd', 15));
    }

    /**
     * 9c. forceRequired() đưa khóa vào trạng thái cần captcha ngay, kể cả khi chưa có bản ghi
     */
    public function testForceRequired()
    {
        $tracker = $this->tracker();

        // Chưa có bản ghi
        $tracker->forceRequired('u1', $this->now);
        $this->assertTrue($tracker->isRequired('u1', $this->now));
        $this->assertSame(self::LIMIT, (int) $this->row('u1')['count']);

        // Đang đếm dưới ngưỡng thì nâng lên ngưỡng
        $this->failTimes($tracker, 'u2', 2, $this->now);
        $tracker->forceRequired('u2', $this->now + 1);
        $this->assertTrue($tracker->isRequired('u2', $this->now + 1));
        $this->assertSame(self::LIMIT, (int) $this->row('u2')['count']);

        // Đã cao hơn ngưỡng thì giữ nguyên count, chỉ kéo dài thời gian cần captcha
        $last = $this->failTimes($tracker, 'u3', self::LIMIT + 3, $this->now);
        $later = $last + self::BAN * 60 - 10;
        $tracker->forceRequired('u3', $later);
        $this->assertSame(self::LIMIT + 3, (int) $this->row('u3')['count']);
        $this->assertTrue($tracker->isRequired('u3', $later + 60));

        // Bộ đếm tắt thì không ghi gì
        $this->tracker(0)->forceRequired('u4', $this->now);
        $this->assertFalse($this->row('u4'));
    }

    /**
     * 10. cleanup() xóa bản ghi hết hiệu lực, giữ bản ghi còn hiệu lực
     */
    public function testCleanup()
    {
        $tracker = $this->tracker();
        $ttl = max(self::WINDOW, self::BAN) * 60;

        // Hết hiệu lực
        $this->failTimes($tracker, 'u1', 2, $this->now - $ttl - 100);
        $this->failTimes($tracker, 'u2', self::LIMIT, $this->now - $ttl - 100);

        // Đang cần captcha
        $this->failTimes($tracker, 'u3', self::LIMIT, $this->now - 60);

        // Đang đếm
        $this->failTimes($tracker, 'u4', 1, $this->now);

        $this->assertSame(2, $tracker->cleanup($this->now));
        $this->assertFalse($this->row('u1'));
        $this->assertFalse($this->row('u2'));
        $this->assertNotFalse($this->row('u3'));
        $this->assertNotFalse($this->row('u4'));
        $this->assertTrue($tracker->isRequired('u3', $this->now));
    }

    /**
     * 11. login_number_tracking = 0 thì tắt hoàn toàn, không ghi gì vào CSDL
     */
    public function testDisabled()
    {
        $tracker = $this->tracker(0);
        $this->failTimes($tracker, 'u1', 100, $this->now);

        $this->assertFalse($tracker->isEnabled());
        $this->assertFalse($tracker->isRequired('u1', $this->now + 100));
        $this->assertFalse($this->row('u1'));
    }

    /**
     * 12. count không tràn cột khi bị dò liên tục
     */
    public function testCountCapped()
    {
        global $db;

        $tracker = $this->tracker();
        $tracker->fail('u1', $this->now);
        $db->exec('UPDATE ' . $this->table . ' SET count = ' . LoginTracker::MAX_COUNT . " WHERE keyname = 'u1'");

        $tracker->fail('u1', $this->now + 1);

        $this->assertSame(LoginTracker::MAX_COUNT, (int) $this->row('u1')['count']);
        $this->assertTrue($tracker->isRequired('u1', $this->now + 1));
    }
}
