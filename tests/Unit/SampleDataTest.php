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
     * Dữ liệu mẫu thống kê click đầy đủ cho toàn bộ banner trong hệ thống
     *
     * Mỗi banner sẽ có ~800 click rải đều trong 6 tháng gần nhất,
     * với đa dạng quốc gia, trình duyệt, hệ điều hành, IP và referrer —
     * đủ để trang info-banner hiển thị đồ thị theo ngày/tháng/quốc gia/browser/OS.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForBannersAllStats()
    {
        global $db, $db_config;

        // Lấy toàn bộ banner hiện có
        $bannerIds = $db->query(
            'SELECT id FROM ' . $db_config['prefix'] . '_banners_rows ORDER BY id ASC'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($bannerIds)) {
            $this->markTestSkipped('Không có banner nào trong bảng ' . $db_config['prefix'] . '_banners_rows.');
        }

        $countries = ['VN', 'US', 'JP', 'KR', 'DE', 'FR', 'SG'];
        $oses = [
            'windows' => 'Windows',
            'android' => 'Android',
            'ios'     => 'iOS',
            'linux'   => 'Linux',
            'macos'   => 'macOS',
        ];
        $browsers = [
            'chrome'  => 'Google Chrome',
            'firefox' => 'Firefox',
            'edge'    => 'Microsoft Edge',
            'safari'  => 'Safari',
            'opera'   => 'Opera',
        ];
        $refs = [
            'https://google.com/search?q=banner',
            'https://facebook.com/',
            'https://zalo.me/',
            'https://example.com/',
            '',
        ];

        $randomIp = function (): string {
            return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
        };

        // Tạo danh sách 6 tháng gần nhất (timestamp bắt đầu và kết thúc từng tháng)
        $months = [];
        $now = time();
        for ($m = 0; $m < 6; $m++) {
            $year  = (int) date('Y', strtotime("-$m months", $now));
            $month = (int) date('n', strtotime("-$m months", $now));
            $months[] = [
                'start' => mktime(0, 0, 0, $month, 1, $year),
                'end'   => mktime(23, 59, 59, $month, (int) date('t', mktime(0, 0, 0, $month, 1, $year)), $year),
            ];
        }

        $countryKeys  = array_values($countries);
        $osKeys       = array_keys($oses);
        $browserKeys  = array_keys($browsers);

        foreach ($bannerIds as $bid) {
            $bid    = (int) $bid;
            $values = [];

            // ~800 click: ~133 per month × 6 months
            foreach ($months as $range) {
                for ($i = 0; $i < 133; $i++) {
                    $time    = rand($range['start'], $range['end']);
                    $day     = (int) date('j', $time);
                    $country = $countryKeys[array_rand($countryKeys)];
                    $osKey   = $osKeys[array_rand($osKeys)];
                    $brKey   = $browserKeys[array_rand($browserKeys)];
                    $ref     = $refs[array_rand($refs)];

                    $values[] = sprintf(
                        "(%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s')",
                        $bid,
                        $time,
                        $day,
                        $randomIp(),
                        $country,
                        $brKey,
                        $browsers[$brKey],
                        $osKey,
                        $oses[$osKey],
                        str_replace(["\\", "'"], ["\\\\", "\\'"], $ref)
                    );
                }
            }

            $db->exec(
                'INSERT INTO ' . $db_config['prefix'] . '_banners_click'
                . ' (bid, click_time, click_day, click_ip, click_country,'
                . ' click_browse_key, click_browse_name, click_os_key, click_os_name, click_ref)'
                . ' VALUES ' . implode(',', $values)
            );
        }

        $this->assertTrue(true);
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

    /**
     * Dữ liệu mẫu nhân viên hỗ trợ (supporter) cho module contact
     *
     * Sinh 10 supporter phân bổ vào các department hiện có.
     * Yêu cầu bảng _contact_department phải có ít nhất 1 bản ghi.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForContactSupporter()
    {
        global $db, $db_config;

        $tablePrefix = $db_config['prefix'] . '_vi_contact';

        // Lấy danh sách department hiện có
        $departmentIds = $db->query(
            'SELECT id FROM ' . $tablePrefix . '_department ORDER BY id ASC'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($departmentIds)) {
            $this->markTestSkipped('Không có department nào trong bảng ' . $tablePrefix . '_department.');
        }

        $fullNames = [
            'Nguyễn Văn An',
            'Trần Thị Bình',
            'Lê Văn Cường',
            'Phạm Thị Dung',
            'Hoàng Văn Em',
            'Huỳnh Thị Phương',
            'Phan Văn Giang',
            'Vũ Thị Hà',
            'Đặng Văn Hùng',
            'Bùi Thị Lan',
        ];

        $deptCount = count($departmentIds);
        $values = [];

        // Lấy weight lớn nhất hiện có theo từng department để tránh trùng khi chạy nhiều lần
        $weightMap = [];
        foreach ($departmentIds as $did) {
            $max = $db->query(
                'SELECT MAX(weight) FROM ' . $tablePrefix . '_supporter WHERE departmentid = ' . (int) $did
            )->fetchColumn();
            $weightMap[(int) $did] = (int) $max;
        }

        foreach ($fullNames as $i => $name) {
            $departmentid = (int) $departmentIds[$i % $deptCount];

            $weightMap[$departmentid] += 1;
            $weight = $weightMap[$departmentid];

            $phone = '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $email = 'supporter' . ($i + 1) . '@example.com';

            $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

            $values[] = sprintf(
                "(%d,'%s','','%s','%s','',1,%d)",
                $departmentid,
                $esc($name),
                $esc($phone),
                $esc($email),
                $weight
            );
        }

        $inserted = $db->exec(
            'INSERT INTO ' . $tablePrefix . '_supporter'
            . ' (departmentid, full_name, image, phone, email, others, act, weight) VALUES '
            . implode(',', $values)
        );

        $this->assertGreaterThan(0, $inserted, 'Không có dòng nào được insert vào bảng ' . $tablePrefix . '_supporter.');
    }

    /**
     * Dữ liệu mẫu thông báo cho màn quản trị danh sách thông báo của module inform
     *
     * Sinh 50 bản ghi ở bảng _inform với đủ kiểu người gửi, người nhận,
     * trạng thái waiting/active/expired/unlimited và thêm trạng thái đọc ở _inform_status.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForInformAdminMain()
    {
        global $db, $db_config;

        $informTable = $db_config['prefix'] . '_inform';
        $statusTable = $db_config['prefix'] . '_inform_status';
        $usersTable = $db_config['prefix'] . '_users';
        $authorsTable = $db_config['prefix'] . '_authors';
        $groupsTable = $db_config['prefix'] . '_users_groups';

        $userIds = $db->query(
            'SELECT userid FROM ' . $usersTable . ' ORDER BY userid ASC LIMIT 30'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($userIds)) {
            $this->markTestSkipped('Không có user nào trong bảng ' . $usersTable . '.');
        }

        $adminIds = $db->query(
            'SELECT admin_id FROM ' . $authorsTable . ' ORDER BY admin_id ASC'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($adminIds)) {
            $this->markTestSkipped('Không có admin nào trong bảng ' . $authorsTable . '.');
        }

        $groupIds = $db->query(
            'SELECT group_id FROM ' . $groupsTable . ' WHERE act = 1 ORDER BY group_id ASC'
        )->fetchAll(\PDO::FETCH_COLUMN);

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $seedMark = 'seed-inform-admin-main-' . date('YmdHis') . '-' . rand(1000, 9999);
        $now = time();
        $values = [];
        $totalRecords = 50;
        $userCount = count($userIds);
        $adminCount = count($adminIds);
        $groupCount = count($groupIds);

        for ($index = 1; $index <= $totalRecords; ++$index) {
            $senderRole = match ($index % 3) {
                1       => 'system',
                2       => $groupCount > 0 ? 'group' : 'admin',
                default => 'admin',
            };

            $senderGroup = $senderRole === 'group' ? (int) $groupIds[($index - 1) % $groupCount] : 0;
            $senderAdmin = $senderRole === 'admin' ? (int) $adminIds[($index - 1) % $adminCount] : 0;

            if ($senderRole === 'group') {
                $receiverGrs = '';
                $receiverIds = [
                    (int) $userIds[($index - 1) % $userCount],
                    (int) $userIds[$index % $userCount],
                ];
                $receiverIds = implode(',', array_values(array_unique($receiverIds)));
            } elseif ($index % 4 === 0) {
                $receiverGrs = '';
                $receiverIds = '';
            } elseif ($index % 4 === 1 && $groupCount > 0) {
                $receiverGroups = [(int) $groupIds[($index - 1) % $groupCount]];
                if ($groupCount > 1 && $index % 8 === 1) {
                    $receiverGroups[] = (int) $groupIds[$index % $groupCount];
                }
                $receiverGrs = implode(',', array_values(array_unique($receiverGroups)));
                $receiverIds = '';
            } else {
                $receiverGrs = '';
                $receiverUsers = [
                    (int) $userIds[($index - 1) % $userCount],
                    (int) $userIds[$index % $userCount],
                ];
                if ($userCount > 2 && $index % 5 === 0) {
                    $receiverUsers[] = (int) $userIds[($index + 1) % $userCount];
                }
                $receiverIds = implode(',', array_values(array_unique($receiverUsers)));
            }

            switch ($index % 5) {
                case 0:
                    $addTime = $now + rand(3600, 5 * 86400);
                    $expTime = $addTime + rand(2, 10) * 86400;
                    break;
                case 1:
                    $addTime = $now - rand(1, 15) * 86400;
                    $expTime = $now + rand(3, 20) * 86400;
                    break;
                case 2:
                    $addTime = $now - rand(1, 30) * 86400;
                    $expTime = 0;
                    break;
                case 3:
                    $addTime = $now - rand(20, 60) * 86400;
                    $expTime = $now - rand(1, 10) * 86400;
                    break;
                default:
                    $addTime = $now - rand(0, 3) * 86400;
                    $expTime = $now + rand(15, 40) * 86400;
                    break;
            }

            $messageData = [
                'isdef' => 'vi',
                'contents' => [
                    'vi' => '[' . $seedMark . '] Thong bao mau #' . $index . ' cho danh sach admin inform',
                    'en' => '[' . $seedMark . '] Sample notification #' . $index . ' for inform admin list',
                ],
            ];

            $linkData = [
                'isdef' => 'vi',
                'contents' => [
                    'vi' => $index % 6 === 0 ? '' : 'index.php?seed=' . $seedMark . '&item=' . $index,
                    'en' => $index % 6 === 0 ? '' : 'https://example.com/' . $seedMark . '/' . $index,
                ],
            ];

            $messageJson = $esc(json_encode($messageData, JSON_UNESCAPED_UNICODE));
            $linkJson = $index % 6 === 0 ? '' : $esc(json_encode($linkData, JSON_UNESCAPED_UNICODE));

            $values[] = sprintf(
                "('%s','%s','%s',%d,%d,'%s','%s',%d,%d)",
                $esc($receiverGrs),
                $esc($receiverIds),
                $senderRole,
                $senderGroup,
                $senderAdmin,
                $messageJson,
                $linkJson,
                $addTime,
                $expTime
            );
        }

        $this->assertCount(50, $values, 'Số lượng bản ghi mẫu tạo cho _inform không đúng 50.');

        $inserted = $db->exec(
            'INSERT INTO ' . $informTable
            . ' (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES '
            . implode(',', $values)
        );

        $this->assertGreaterThan(0, $inserted, 'Không có dòng nào được insert vào bảng ' . $informTable . '.');

        $informIds = $db->query(
            "SELECT id FROM " . $informTable . " WHERE message LIKE '%" . $esc($seedMark) . "%' ORDER BY id ASC"
        )->fetchAll(\PDO::FETCH_COLUMN);

        $this->assertCount(50, $informIds, 'Không lấy đủ 50 bản ghi vừa insert từ bảng ' . $informTable . '.');

        $statusValues = [];
        $statusUsers = array_slice($userIds, 0, min(6, $userCount));

        foreach (array_slice($informIds, 0, 24) as $index => $pid) {
            $pid = (int) $pid;

            foreach ($statusUsers as $offset => $userId) {
                if ($offset === 2 && $index % 2 !== 0) {
                    continue;
                }

                $shownTime = $now - rand(600, 20 * 86400);
                $viewedTime = $offset === 2 ? 0 : $shownTime + rand(60, 7200);
                $favoriteTime = ($index % 4 === 0 && $offset === 0) ? $shownTime + rand(120, 3600) : 0;
                $hiddenTime = ($index % 5 === 0 && $offset === 1) ? $shownTime + rand(180, 5400) : 0;

                $statusValues[] = sprintf(
                    '(%d,%d,%d,%d,%d,%d)',
                    $pid,
                    (int) $userId,
                    $shownTime,
                    $viewedTime,
                    $favoriteTime,
                    $hiddenTime
                );
            }
        }

        if (!empty($statusValues)) {
            $db->exec(
                'INSERT IGNORE INTO ' . $statusTable
                . ' (pid, userid, shown_time, viewed_time, favorite_time, hidden_time) VALUES '
                . implode(',', $statusValues)
            );
        }

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu giọng đọc (voices) cho module news
     *
     * Sinh 4 giọng đọc mẫu (Nam Bắc, Nữ Bắc, Nam Nam, Nữ Nam).
     * Weight tự động tăng từ MAX(weight) hiện có trong bảng.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForNewsVoices()
    {
        global $db, $db_config;

        $table = $db_config['prefix'] . '_vi_news_voices';

        // Lấy weight lớn nhất hiện có để tự động tăng tiếp
        $maxWeight = (int) $db->query('SELECT MAX(weight) FROM ' . $table)->fetchColumn();

        $now = time();
        $voices = [
            ['voice_key' => 'male-north',   'title' => 'Giọng Nam Bắc',  'description' => 'Giọng đọc nam giới vùng miền Bắc'],
            ['voice_key' => 'female-north', 'title' => 'Giọng Nữ Bắc',  'description' => 'Giọng đọc nữ giới vùng miền Bắc'],
            ['voice_key' => 'male-south',   'title' => 'Giọng Nam Nam',  'description' => 'Giọng đọc nam giới vùng miền Nam'],
            ['voice_key' => 'female-south', 'title' => 'Giọng Nữ Nam',  'description' => 'Giọng đọc nữ giới vùng miền Nam'],
        ];

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $values = [];
        foreach ($voices as $i => $v) {
            $weight = $maxWeight + $i + 1;
            $values[] = sprintf(
                "('%s','%s','%s',%d,%d,%d,1)",
                $esc($v['voice_key']),
                $esc($v['title']),
                $esc($v['description']),
                $now,
                0,
                $weight
            );
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $table
            . ' (voice_key, title, description, add_time, edit_time, weight, status) VALUES '
            . implode(',', $values)
        );

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu nguồn tin cho màn quản trị nguồn của module news
     *
     * Sinh 50 nguồn tin với title duy nhất, domain hợp lệ và weight tăng tiếp
     * từ dữ liệu hiện có để màn sources có đủ dữ liệu phân trang và sắp xếp.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForNewsSources()
    {
        global $db, $db_config;

        $table = $db_config['prefix'] . '_vi_news_sources';
        $maxWeight = (int) $db->query('SELECT MAX(weight) FROM ' . $table)->fetchColumn();

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $domains = [
            ['VnExpress', 'https://vnexpress.net'],
            ['Tuoi Tre', 'https://tuoitre.vn'],
            ['Thanh Nien', 'https://thanhnien.vn'],
            ['Dan Tri', 'https://dantri.com.vn'],
            ['VietnamNet', 'https://vietnamnet.vn'],
            ['Nguoi Lao Dong', 'https://nld.com.vn'],
            ['Reuters', 'https://www.reuters.com'],
            ['Associated Press', 'https://apnews.com'],
            ['BBC News', 'https://www.bbc.com'],
            ['CNN', 'https://www.cnn.com'],
        ];

        $batchMark = 'seed-sources-' . date('YmdHis') . '-' . rand(1000, 9999);
        $now = time();
        $values = [];

        for ($index = 1; $index <= 50; ++$index) {
            [$name, $link] = $domains[($index - 1) % count($domains)];
            $weight = $maxWeight + $index;
            $addTime = $now - rand(0, 45 * 86400);
            $editTime = $addTime + rand(0, 7 * 86400);
            if ($editTime > $now) {
                $editTime = $now;
            }

            $title = $name . ' Sample Source ' . str_pad((string) $index, 2, '0', STR_PAD_LEFT) . ' [' . $batchMark . ']';

            $values[] = sprintf(
                "('%s','%s','',%d,%d,%d)",
                $esc($title),
                $esc($link),
                $weight,
                $addTime,
                $editTime
            );
        }

        $this->assertCount(50, $values, 'Số lượng nguồn tin mẫu tạo ra không đúng 50 bản ghi.');

        $inserted = $db->exec(
            'INSERT INTO ' . $table
            . ' (title, link, logo, weight, add_time, edit_time) VALUES '
            . implode(',', $values)
        );

        $this->assertGreaterThan(0, $inserted, 'Không có dòng nào được insert vào bảng ' . $table . '.');
    }

    /**
     * Dữ liệu mẫu theo dòng sự kiện cho màn quản trị topics của module news
     *
     * Sinh 50 topic ở bảng ngôn ngữ vi với title, alias duy nhất,
     * weight tăng tiếp từ dữ liệu hiện có để màn quản trị có đủ dữ liệu hiển thị.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForNewsAdminTopics()
    {
        global $db, $db_config;

        $table = $db_config['prefix'] . '_vi_news_topics';
        $maxWeight = (int) $db->query('SELECT MAX(weight) FROM ' . $table)->fetchColumn();

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $topicSeeds = [
            ['Kinh tế', 'Dòng sự kiện về kinh tế, doanh nghiệp và thị trường', 'kinh-te'],
            ['Chính trị', 'Theo dõi các diễn biến chính trị nổi bật trong nước và quốc tế', 'chinh-tri'],
            ['Xã hội', 'Tin tức xã hội, dân sinh và các vấn đề cộng đồng', 'xa-hoi'],
            ['Giáo dục', 'Cập nhật chuyển động giáo dục, tuyển sinh và trường học', 'giao-duc'],
            ['Y tế', 'Diễn biến lĩnh vực y tế, bệnh viện và chăm sóc sức khỏe', 'y-te'],
            ['Công nghệ', 'Tin công nghệ, AI, chuyển đổi số và sản phẩm mới', 'cong-nghe'],
            ['Thể thao', 'Lịch thi đấu, kết quả và sự kiện thể thao đáng chú ý', 'the-thao'],
            ['Giải trí', 'Sự kiện văn hóa, điện ảnh, âm nhạc và người nổi tiếng', 'giai-tri'],
            ['Pháp luật', 'Thông tin pháp luật, điều tra và các vụ việc quan trọng', 'phap-luat'],
            ['Môi trường', 'Biến đổi khí hậu, tài nguyên và các vấn đề môi trường', 'moi-truong'],
        ];

        $batchMark = 'vi-topic-' . date('YmdHis') . '-' . rand(1000, 9999);
        $now = time();
        $values = [];

        for ($index = 1; $index <= 50; ++$index) {
            [$baseTitle, $baseDescription, $baseAlias] = $topicSeeds[($index - 1) % count($topicSeeds)];

            $weight = $maxWeight + $index;
            $addTime = $now - rand(0, 90 * 86400);
            $editTime = $addTime + rand(0, 10 * 86400);
            if ($editTime > $now) {
                $editTime = $now;
            }

            $title = $baseTitle . ' - Chủ đề mẫu ' . str_pad((string) $index, 2, '0', STR_PAD_LEFT) . ' [' . $batchMark . ']';
            $alias = $baseAlias . '-sample-' . str_pad((string) $index, 2, '0', STR_PAD_LEFT) . '-' . $batchMark;
            $description = $baseDescription . ' [seed ' . $index . ']';
            $keywords = implode(', ', [$baseTitle, 'chu de mau', 'news topic', 'seed ' . $index]);

            $values[] = sprintf(
                "('%s','%s','','%s',%d,'%s',%d,%d)",
                $esc($title),
                $esc($alias),
                $esc($description),
                $weight,
                $esc($keywords),
                $addTime,
                $editTime
            );
        }

        $this->assertCount(50, $values, 'Số lượng topic mẫu tạo ra không đúng 50 bản ghi.');

        $inserted = $db->exec(
            'INSERT INTO ' . $table
            . ' (title, alias, image, description, weight, keywords, add_time, edit_time) VALUES '
            . implode(',', $values)
        );

        $this->assertGreaterThan(0, $inserted, 'Không có dòng nào được insert vào bảng ' . $table . '.');
    }

    /**
     * Dữ liệu mẫu nhóm tin cho màn quản trị groups của module news
     *
     * Sinh 10 nhóm tin ở bảng vi_news_block_cat với weight tăng liên tục,
     * đồng thời gán bài viết mẫu vào vi_news_block nếu đã có bài trong hệ thống.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForNewsGroups()
    {
        global $db, $db_config;

        $blockCatTable = $db_config['prefix'] . '_vi_news_block_cat';
        $blockTable = $db_config['prefix'] . '_vi_news_block';
        $rowsTable = $db_config['prefix'] . '_vi_news_rows';

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $maxWeight = (int) $db->query('SELECT MAX(weight) FROM ' . $blockCatTable)->fetchColumn();
        $batchMark = 'seed-news-groups-' . date('YmdHis') . '-' . rand(1000, 9999);
        $now = time();

        $groupSeeds = [
            ['Tin nổi bật', 'Nhom tin noi bat tren trang chu'],
            ['Tin cập nhật', 'Nhom tong hop tin cap nhat nhanh trong ngay'],
            ['Phân tích', 'Nhom bai viet phan tich va goc nhin chuyen sau'],
            ['Tiêu điểm', 'Nhom bai viet duoc dat o vi tri tieu diem'],
            ['Dữ liệu', 'Nhom tin su kien kem so lieu va du lieu'],
            ['Theo dòng sự kiện', 'Nhom tong hop bai viet theo dong su kien'],
            ['Góc nhìn', 'Nhom bai viet binh luan va goc nhin'],
            ['Đọc nhiều', 'Nhom bai viet doc nhieu va duoc quan tam'],
            ['Nổi bật tuần', 'Nhom bai viet noi bat trong tuan'],
            ['Khuyến nghị', 'Nhom bai viet goi y cho bien tap vien'],
        ];

        $groupValues = [];

        foreach ($groupSeeds as $index => [$baseTitle, $baseDescription]) {
            $position = $index + 1;
            $title = $baseTitle . ' [' . $batchMark . ' #' . str_pad((string) $position, 2, '0', STR_PAD_LEFT) . ']';
            $alias = 'news-group-' . $batchMark . '-' . str_pad((string) $position, 2, '0', STR_PAD_LEFT);
            $description = $baseDescription . ' [' . $batchMark . ']';
            $keywords = implode(', ', [$baseTitle, 'news groups', 'seed data', 'vi']);
            $weight = $maxWeight + $position;
            $addDefault = $position === 1 ? 1 : 0;
            $numbers = 3 + ($index % 6);

            $groupValues[] = sprintf(
                "(%d,%d,'%s','%s','','%s',%d,'%s',%d,%d)",
                $addDefault,
                $numbers,
                $esc($title),
                $esc($alias),
                $esc($description),
                $weight,
                $esc($keywords),
                $now,
                $now
            );
        }

        $this->assertCount(10, $groupValues, 'Số lượng nhóm tin mẫu tạo ra không đúng 10 bản ghi.');

        $insertedGroups = $db->exec(
            'INSERT INTO ' . $blockCatTable
            . ' (adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES '
            . implode(',', $groupValues)
        );

        $this->assertGreaterThan(0, $insertedGroups, 'Không có nhóm tin nào được insert vào bảng ' . $blockCatTable . '.');

        $insertedBids = $db->query(
            "SELECT bid FROM " . $blockCatTable . " WHERE alias LIKE 'news-group-" . $esc($batchMark) . "-%' ORDER BY bid ASC"
        )->fetchAll(\PDO::FETCH_COLUMN);

        $this->assertCount(10, $insertedBids, 'Không lấy đủ 10 nhóm tin vừa insert từ bảng ' . $blockCatTable . '.');

        $rowIds = $db->query(
            'SELECT id FROM ' . $rowsTable . ' ORDER BY publtime DESC, id DESC LIMIT 30'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (!empty($rowIds)) {
            $blockValues = [];
            $rowCount = count($rowIds);

            foreach ($insertedBids as $groupIndex => $bid) {
                $bid = (int) $bid;

                for ($weight = 1; $weight <= 3; ++$weight) {
                    $rowId = (int) $rowIds[(($groupIndex * 3) + ($weight - 1)) % $rowCount];
                    $blockValues[] = sprintf('(%d,%d,%d)', $bid, $rowId, $weight);
                }
            }

            if (!empty($blockValues)) {
                $db->exec(
                    'INSERT IGNORE INTO ' . $blockTable
                    . ' (bid, id, weight) VALUES '
                    . implode(',', $blockValues)
                );
            }
        }

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu tác giả (author) cho module News
     *
     * Mỗi user trong nv5_users được tạo 1 dòng tác giả tương ứng.
     * Dùng INSERT IGNORE để bỏ qua nếu uid hoặc alias đã tồn tại.
     * Nếu không có dòng nào được insert thì không xem là lỗi.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForNewsAuthors()
    {
        global $db, $db_config;

        $authorTable = $db_config['prefix'] . '_vi_news_author';

        // Lấy toàn bộ user hiện có
        $stmt = $db->query(
            'SELECT userid, username, first_name, last_name FROM ' . $db_config['prefix'] . '_users ORDER BY userid ASC'
        );
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($users)) {
            $this->markTestSkipped('Không có user nào trong bảng ' . $db_config['prefix'] . '_users.');
        }

        $esc = fn(string $s): string => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $now    = time();
        $values = [];

        foreach ($users as $user) {
            $uid = (int) $user['userid'];

            // Pseudonym = Họ + Tên, fallback về username nếu thiếu
            $firstName = trim($user['first_name'] ?? '');
            $lastName  = trim($user['last_name'] ?? '');
            $pseudonym = trim($lastName . ' ' . $firstName);
            if ($pseudonym === '') {
                $pseudonym = $user['username'];
            }

            // Alias = username viết thường, thay _ bằng - (unique vì username đã unique)
            $alias = strtolower(str_replace('_', '-', $user['username']));

            $values[] = sprintf(
                "(%d,'%s','%s','','%s',%d,0,1,0)",
                $uid,
                $esc($alias),
                $esc($pseudonym),
                '',
                $now
            );
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $authorTable
            . ' (uid, alias, pseudonym, image, description, add_time, edit_time, active, numnews) VALUES '
            . implode(',', $values)
        );

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu thống kê click cho trang stats của module banners (site/client)
     *
     * Sinh click data cho các banner đã có clid > 0 trong _banners_rows,
     * rải đều trong 3 tháng gần nhất với đa dạng quốc gia, trình duyệt, OS và referrer —
     * đủ để trang stats hiển thị đồ thị theo ngày/tháng/quốc gia/browser/OS cho khách hàng.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForBannersClientStats()
    {
        global $db, $db_config;

        $rowsTable = $db_config['prefix'] . '_banners_rows';
        $clickTable = $db_config['prefix'] . '_banners_click';

        // Lấy các banner đã gán cho khách hàng (clid > 0)
        $bannerIds = $db->query(
            'SELECT id FROM ' . $rowsTable . ' WHERE clid > 0 ORDER BY id ASC'
        )->fetchAll(\PDO::FETCH_COLUMN);

        // Fallback: lấy tất cả banner nếu không có banner nào có clid
        if (empty($bannerIds)) {
            $bannerIds = $db->query(
                'SELECT id FROM ' . $rowsTable . ' ORDER BY id ASC'
            )->fetchAll(\PDO::FETCH_COLUMN);
        }

        if (empty($bannerIds)) {
            $this->markTestSkipped('Không có banner nào trong bảng ' . $rowsTable . '.');
        }

        $countries   = ['VN', 'US', 'JP', 'KR', 'DE', 'FR', 'SG'];
        $oses        = [
            'windows' => 'Windows',
            'android' => 'Android',
            'ios'     => 'iOS',
            'linux'   => 'Linux',
            'macos'   => 'macOS',
        ];
        $browsers    = [
            'chrome'  => 'Google Chrome',
            'firefox' => 'Firefox',
            'edge'    => 'Microsoft Edge',
            'safari'  => 'Safari',
            'opera'   => 'Opera',
        ];
        $refs = [
            'https://google.com/search?q=banner',
            'https://facebook.com/',
            'https://zalo.me/',
            'https://example.com/',
            '',
        ];

        $randomIp = function (): string {
            return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
        };

        // Tạo danh sách 3 tháng gần nhất
        $months = [];
        $now = time();
        for ($m = 0; $m < 3; $m++) {
            $year  = (int) date('Y', strtotime("-$m months", $now));
            $month = (int) date('n', strtotime("-$m months", $now));
            $months[] = [
                'start' => mktime(0, 0, 0, $month, 1, $year),
                'end'   => mktime(23, 59, 59, $month, (int) date('t', mktime(0, 0, 0, $month, 1, $year)), $year),
            ];
        }

        $countryKeys = array_values($countries);
        $osKeys      = array_keys($oses);
        $browserKeys = array_keys($browsers);

        foreach ($bannerIds as $bid) {
            $bid    = (int) $bid;
            $values = [];

            // ~150 click/tháng × 3 tháng = ~450 click/banner
            foreach ($months as $range) {
                for ($i = 0; $i < 150; $i++) {
                    $time    = rand($range['start'], $range['end']);
                    $day     = (int) date('j', $time);
                    $country = $countryKeys[array_rand($countryKeys)];
                    $osKey   = $osKeys[array_rand($osKeys)];
                    $brKey   = $browserKeys[array_rand($browserKeys)];
                    $ref     = $refs[array_rand($refs)];

                    $values[] = sprintf(
                        "(%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s')",
                        $bid,
                        $time,
                        $day,
                        $randomIp(),
                        $country,
                        $brKey,
                        $browsers[$brKey],
                        $osKey,
                        $oses[$osKey],
                        str_replace(["\\", "'"], ["\\\\", "\\'"], $ref)
                    );
                }
            }

            $db->exec(
                'INSERT INTO ' . $clickTable
                . ' (bid, click_time, click_day, click_ip, click_country,'
                . ' click_browse_key, click_browse_name, click_os_key, click_os_name, click_ref)'
                . ' VALUES ' . implode(',', $values)
            );
        }

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu thống kê nguồn truy cập (referer) cho bảng nv5_vi_referer_stats
     *
     * Mỗi host referer được gán số lượt truy cập ngẫu nhiên cho từng tháng
     * trong năm, cùng tổng cộng và thời gian cập nhật cuối.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForStatisticsRefererStats()
    {
        global $db, $db_config;

        $table = $db_config['prefix'] . '_vi_referer_stats';

        // Danh sách referer host thực tế
        $hosts = [
            'google.com',
            'google.com.vn',
            'facebook.com',
            'zalo.me',
            'bing.com',
            'yahoo.com',
            't.co',
            'youtube.com',
            'vnexpress.net',
            'dantri.com.vn',
            'tuoitre.vn',
            'baidu.com',
            'reddit.com',
            'linkedin.com',
            'tiktok.com',
            'instagram.com',
            'news.ycombinator.com',
            'stackoverflow.com',
        ];

        $now    = time();
        $values = [];
        $esc    = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        // Các host có traffic cao hơn
        $topHosts = ['google.com', 'google.com.vn', 'facebook.com', 'zalo.me'];

        foreach ($hosts as $host) {
            $isTop     = in_array($host, $topHosts, true);
            $monthData = [];
            $total     = 0;

            for ($m = 0; $m < 12; $m++) {
                $count       = $isTop ? rand(500, 3000) : rand(10, 400);
                $monthData[] = $count;
                $total      += $count;
            }

            $values[] = sprintf(
                "('%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",
                $esc($host),
                $total,
                $monthData[0],
                $monthData[1],
                $monthData[2],
                $monthData[3],
                $monthData[4],
                $monthData[5],
                $monthData[6],
                $monthData[7],
                $monthData[8],
                $monthData[9],
                $monthData[10],
                $monthData[11],
                $now - rand(0, 86400 * 30)
            );
        }

        // Cột: host, total, month01..month12, last_update
        $db->exec(
            'INSERT IGNORE INTO ' . $table
            . ' (host, total, month01, month02, month03, month04, month05, month06,'
            . '  month07, month08, month09, month10, month11, month12, last_update)'
            . ' VALUES ' . implode(',', $values)
        );

        $this->assertTrue(true);
    }
}
