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

use Tests\Support\UnitTester;

class SampleDataTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Dữ liệu mẫu click quảng cáo
     *
     * @group sample-data
     */
    public function testInsertSampleDataForBannerClicks()
    {
        global $db, $db_config;

        $year = (int) date('Y');
        $startTime = strtotime("$year-01-01 00:00:00");
        $endTime   = strtotime("$year-01-31 23:59:59");

        $countries = ['VN', 'US', 'JP', 'KR', 'DE'];
        $oses = [
            'windows' => 'Windows',
            'android' => 'Android',
            'ios'     => 'iOS',
            'linux'   => 'Linux',
            'macos'   => 'macOS'
        ];
        $browsers = [
            'chrome'  => 'Google Chrome',
            'firefox' => 'Firefox',
            'edge'    => 'Microsoft Edge',
            'safari'  => 'Safari',
            'opera'   => 'Opera'
        ];

        $buildRows = function ($rows) {
            return implode(',', $rows);
        };

        $randomIp = function () {
            return rand(1,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
        };

        /* =======================
        * 1. 5000 records theo quốc gia
        * ======================= */
        $values = [];

        for ($i = 0; $i < 5000; $i++) {
            $time = rand($startTime, $endTime);
            $day  = (int)date('j', $time);

            $country = $countries[array_rand($countries)];
            $osKey   = array_rand($oses);
            $brKey   = array_rand($browsers);

            $values[] = sprintf(
                "(1,%d,%d,'%s','%s','%s','%s','%s','%s','%s')",
                $time,
                $day,
                $randomIp(),
                $country,
                $brKey,
                $browsers[$brKey],
                $osKey,
                $oses[$osKey],
                'https://example.com/?c='.uniqid()
            );
        }

        $db->exec("
            INSERT INTO nv5_banners_click
            (bid, click_time, click_day, click_ip, click_country,
            click_browse_key, click_browse_name,
            click_os_key, click_os_name, click_ref)
            VALUES " . $buildRows($values)
        );

        /* =======================
        * 2. 5000 records theo OS (100 mỗi OS)
        * ======================= */
        $values = [];

        foreach ($oses as $osKey => $osName) {
            for ($i = 0; $i < 1000; $i++) {
                $time = rand($startTime, $endTime);
                $day  = (int)date('j', $time);

                $brKey = array_rand($browsers);

                $values[] = sprintf(
                    "(1,%d,%d,'%s','VN','%s','%s','%s','%s','%s')",
                    $time,
                    $day,
                    $randomIp(),
                    $brKey,
                    $browsers[$brKey],
                    $osKey,
                    $osName,
                    'https://example.com/?os='.uniqid()
                );
            }
        }

        $db->exec("
            INSERT INTO " . $db_config['prefix'] . "_banners_click
            (bid, click_time, click_day, click_ip, click_country,
            click_browse_key, click_browse_name,
            click_os_key, click_os_name, click_ref)
            VALUES " . $buildRows($values)
        );

        /* =======================
        * 3. 5000 records theo Browser (100 mỗi browser)
        * ======================= */
        $values = [];

        foreach ($browsers as $brKey => $brName) {
            for ($i = 0; $i < 1000; $i++) {
                $time = rand($startTime, $endTime);
                $day  = (int)date('j', $time);

                $osKey = array_rand($oses);

                $values[] = sprintf(
                    "(1,%d,%d,'%s','US','%s','%s','%s','%s','%s')",
                    $time,
                    $day,
                    $randomIp(),
                    $brKey,
                    $brName,
                    $osKey,
                    $oses[$osKey],
                    'https://example.com/?br='.uniqid()
                );
            }
        }

        $db->exec("
            INSERT INTO " . $db_config['prefix'] . "_banners_click
            (bid, click_time, click_day, click_ip, click_country,
            click_browse_key, click_browse_name,
            click_os_key, click_os_name, click_ref)
            VALUES " . $buildRows($values)
        );

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu OpenID (OAuth) cho tất cả tài khoản trong nv5_users
     *
     * Mỗi user sẽ được gán ngẫu nhiên 1–3 kết nối OAuth từ các provider:
     * google, google-identity, facebook, zalo.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersOpenid()
    {
        global $db, $db_config;

        // Lấy toàn bộ user hiện có
        $stmt = $db->query('SELECT userid, email FROM ' . $db_config['prefix'] . '_users ORDER BY userid ASC');
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($users)) {
            $this->markTestSkipped('Không có user nào trong bảng ' . $db_config['prefix'] . '_users.');
        }

        // Danh sách provider thực tế của NukeViet
        $providers = ['google', 'google-identity', 'facebook', 'zalo'];

        // Tạo opid ngẫu nhiên 10–18 chữ số (giả lập ID từ provider)
        $randomOpid = function () {
            return (string) rand(1000000000, 9999999999) . rand(100000000, 999999999);
        };

        // Tạo id (OpenID URL hoặc sub) theo từng provider
        $buildId = function (string $provider, string $opid): string {
            return match ($provider) {
                'google', 'google-identity' => $opid,             // Google dùng "sub" = chuỗi số
                'facebook'                  => $opid,             // Facebook dùng numeric ID
                'zalo'                      => $opid,             // Zalo dùng numeric ID
                default                     => $opid,
            };
        };

        $values = [];

        foreach ($users as $user) {
            $userid = (int) $user['userid'];
            $email  = $user['email'];

            // Mỗi user kết nối ngẫu nhiên 1–3 provider, không trùng provider
            $shuffled = $providers;
            shuffle($shuffled);
            $count = rand(1, min(3, count($shuffled)));
            $selectedProviders = array_slice($shuffled, 0, $count);

            foreach ($selectedProviders as $provider) {
                $opid  = $randomOpid();
                $id    = $buildId($provider, $opid);

                $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

                $values[] = sprintf(
                    "(%d,'%s','%s','%s','%s')",
                    $userid,
                    $esc($provider),
                    $esc($opid),
                    $esc($id),
                    $esc($email)
                );
            }
        }

        $this->assertNotEmpty($values, 'Không có dòng nào được tạo để insert.');

        $inserted = $db->exec(
            'INSERT IGNORE INTO ' . $db_config['prefix'] . '_users_openid'
            . ' (userid, openid, opid, id, email) VALUES '
            . implode(',', $values)
        );

        $this->assertGreaterThan(0, $inserted, 'Không có dòng nào được insert vào bảng _users_openid.');
    }

    /**
     * Dữ liệu mẫu thông tin chỉnh sửa chờ duyệt (editcensor) cho module users
     *
     * Mỗi user sẽ có 1 bản ghi trong _users_edit với:
     * - info_basic: JSON các trường hệ thống (first_name, last_name, gender, birthday, sig, view_mail)
     * - info_custom: JSON các trường tùy biến từ _users_field (is_system=0, user_editable=1)
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersEditCensor()
    {
        global $db, $db_config;

        // Lấy toàn bộ user hiện có
        $users = $db->query(
            'SELECT userid FROM ' . $db_config['prefix'] . '_users ORDER BY userid ASC'
        )->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($users)) {
            $this->markTestSkipped('Không có user nào trong bảng ' . $db_config['prefix'] . '_users.');
        }

        // Lấy các trường tùy biến (non-system, user_editable=1) từ _users_field
        $customFields = $db->query(
            'SELECT field, field_type, field_choices FROM ' . $db_config['prefix'] . '_users_field'
            . ' WHERE is_system = 0 AND user_editable = 1 ORDER BY weight ASC'
        )->fetchAll(\PDO::FETCH_ASSOC);

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Đặng', 'Bùi'];
        $lastNames  = ['An', 'Bình', 'Cường', 'Dũng', 'Giang', 'Hà', 'Hùng', 'Linh', 'Long', 'Minh',
                       'Nam', 'Ngọc', 'Phúc', 'Quân', 'Thành', 'Tuấn', 'Uyên', 'Việt', 'Xuân', 'Yên'];
        $genders    = ['M', 'F', ''];
        $sigs       = ['Lập trình viên NukeViet', 'Thành viên mới', 'Hello World!', '', 'Yêu thích mã nguồn mở'];

        $year      = (int) date('Y');
        $startTime = mktime(0, 0, 0, 1, 1, $year - 40);
        $endTime   = mktime(0, 0, 0, 12, 31, $year - 18);

        // Hàm sinh giá trị ngẫu nhiên theo field_type
        $randomValue = function (array $field) use ($startTime, $endTime): string {
            $choices = [];
            if (!empty($field['field_choices'])) {
                $parsed = @unserialize($field['field_choices']);
                if (is_array($parsed)) {
                    $choices = array_keys($parsed);
                }
            }

            return match ($field['field_type']) {
                'number'                    => (string) rand(1, 100),
                'date'                      => (string) rand($startTime, $endTime),
                'select', 'radio'           => !empty($choices) ? (string) $choices[array_rand($choices)] : '',
                'checkbox', 'multiselect'   => !empty($choices)
                    ? implode(',', array_slice($choices, 0, rand(1, min(2, count($choices)))))
                    : '',
                'file'                      => '', // bỏ qua trường file
                default                     => 'sample_' . substr(md5((string) rand()), 0, 8),
            };
        };

        $values = [];

        foreach ($users as $user) {
            $userid = (int) $user['userid'];

            // info_basic: các trường hệ thống
            $infoBasic = [
                'first_name' => $firstNames[array_rand($firstNames)],
                'last_name'  => $lastNames[array_rand($lastNames)],
                'gender'     => $genders[array_rand($genders)],
                'birthday'   => rand($startTime, $endTime),
                'sig'        => $sigs[array_rand($sigs)],
                'view_mail'  => rand(0, 1),
            ];

            // info_custom: các trường tùy biến, mỗi user chọn ngẫu nhiên một số trường
            $infoCustom = [];
            if (!empty($customFields)) {
                $randKeys = array_rand($customFields, rand(1, count($customFields)));
                $picked   = array_intersect_key($customFields, array_flip((array) $randKeys));
                foreach ($picked as $cf) {
                    $val = $randomValue($cf);
                    if ($val !== '') {
                        $infoCustom[$cf['field']] = $val;
                    }
                }
            }

            $lastedit       = rand(strtotime("$year-01-01"), strtotime("$year-12-31"));
            $infoBasicJson  = $esc(json_encode($infoBasic, JSON_UNESCAPED_UNICODE));
            $infoCustomJson = $esc(!empty($infoCustom) ? json_encode($infoCustom, JSON_UNESCAPED_UNICODE) : '');

            $values[] = sprintf(
                "(%d,%d,'%s','%s')",
                $userid,
                $lastedit,
                $infoBasicJson,
                $infoCustomJson
            );
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $db_config['prefix'] . '_users_edit'
            . ' (userid, lastedit, info_basic, info_custom) VALUES '
            . implode(',', $values)
        );

        $this->assertTrue(true);
    }
}
