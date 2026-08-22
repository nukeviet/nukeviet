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

    /**
     * Sinh dữ liệu mẫu bot visits cho bảng _counter (c_type='bot')
     * Dùng cho trang allbots hiển thị danh sách bot đã crawl site
     *
     * @group sample-data
     */
    public function testInsertSampleDataForStatisticsBots()
    {
        global $db, $db_config;

        $table = $db_config['prefix'] . '_counter';
        $now   = time();

        // Danh sách bot thực tế thường gặp kèm lượng request mẫu
        $bots = [
            ['googlebot',           rand(5000, 20000)],
            ['bingbot',             rand(2000, 8000)],
            ['ahrefsbot',           rand(1000, 5000)],
            ['semrushbot',          rand(800,  4000)],
            ['msnbot',              rand(600,  3000)],
            ['yandexbot',           rand(400,  2000)],
            ['dotbot',              rand(300,  1500)],
            ['coccocbot',           rand(200,  1000)],
            ['baiduspider',         rand(150,  800)],
            ['duckduckbot',         rand(100,  600)],
            ['facebookexternalhit', rand(80,   400)],
            ['twitterbot',          rand(60,   300)],
            ['sogou',               rand(50,   200)],
            ['applebot',            rand(40,   150)],
            ['yahooslurp',          rand(30,   100)],
            ['petalbot',            rand(20,   80)],
            ['bytespider',          rand(15,   60)],
            ['gptbot',              rand(10,   50)],
            ['claudebot',           rand(8,    40)],
            ['amazonbot',           rand(5,    20)],
        ];

        $values = [];
        foreach ($bots as [$name, $count]) {
            $values[] = sprintf(
                "('bot','%s',%d,%d,0)",
                str_replace(["\\", "'"], ["\\\\", "\\'"], $name),
                $now - rand(0, 86400 * 60),
                $count
            );
        }

        // c_type + c_val là PK — ON DUPLICATE KEY UPDATE để ghi đè c_count nếu row đã tồn tại
        $db->exec(
            'INSERT INTO ' . $table
            . ' (c_type, c_val, last_update, c_count, vi_count) VALUES '
            . implode(',', $values)
            . ' ON DUPLICATE KEY UPDATE c_count = VALUES(c_count), last_update = VALUES(last_update)'
        );

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu người quan tâm (followers) cho màn quản trị followers của module zalo
     *
     * Sinh 50 follower vào _zalo_followers với app_id khớp zaloAppID đang cấu hình
     * (để hiển thị đúng trên trang), kèm 6 nhãn ở _zalo_tags và gán nhãn cho ~một nửa
     * follower (đồng bộ cả cột tags_info dạng CSV lẫn bảng _zalo_tags_follower).
     *
     * @group sample-data
     */
    public function testInsertSampleDataForZaloFollowers()
    {
        global $db, $db_config;

        $followersTable = $db_config['prefix'] . '_zalo_followers';
        $tagsTable = $db_config['prefix'] . '_zalo_tags';
        $tagsFollowerTable = $db_config['prefix'] . '_zalo_tags_follower';

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        // Lấy zaloAppID đang cấu hình để follower seed khớp bộ lọc app_id của trang.
        // Trang followers lọc: WHERE isfollow=1 AND app_id=:app_id
        $appId = (string) $db->query(
            "SELECT config_value FROM " . $db_config['prefix'] . "_config"
            . " WHERE config_name = 'zaloAppID' LIMIT 1"
        )->fetchColumn();
        if ($appId === '') {
            // Fallback khi site chưa cấu hình Zalo: dùng app_id mẫu cố định
            $appId = 'sample-zalo-app';
        }

        // 6 nhãn mẫu (alias => name) cho người quan tâm
        $tags = [
            'khach-tiem-nang' => 'Khách tiềm năng',
            'da-mua-hang'     => 'Đã mua hàng',
            'cham-soc'        => 'Đang chăm sóc',
            'vip'             => 'Khách VIP',
            'khieu-nai'       => 'Có khiếu nại',
            'doi-tac'         => 'Đối tác',
        ];

        $tagValues = [];
        foreach ($tags as $alias => $name) {
            $tagValues[] = sprintf("('%s','%s')", $esc($alias), $esc($name));
        }
        $db->exec(
            'INSERT IGNORE INTO ' . $tagsTable . ' (alias, name) VALUES ' . implode(',', $tagValues)
        );

        $tagAliases = array_keys($tags);

        // Họ tên người Việt ngẫu nhiên
        $ho   = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương'];
        $dem  = ['Văn', 'Thị', 'Hữu', 'Đức', 'Ngọc', 'Minh', 'Quang', 'Thanh', 'Gia', 'Khánh'];
        $ten  = ['An', 'Bình', 'Cường', 'Dung', 'Em', 'Phương', 'Giang', 'Hà', 'Hùng', 'Lan', 'Long', 'Minh', 'Nam', 'Phúc', 'Quân', 'Thành', 'Tuấn', 'Uyên', 'Việt', 'Yến'];

        // Một vài city_id/district_id hợp lệ theo data/vnsubdivisions.php (HN=01, HCM=79)
        $cities = [
            ['01', '001'], // Hà Nội - Ba Đình
            ['79', '760'], // TP.HCM - Quận 1
            ['48', '490'], // Đà Nẵng - Hải Châu
            ['', ''],      // không khai báo địa chỉ
        ];

        $maxWeight = (int) $db->query('SELECT MAX(weight) FROM ' . $followersTable)->fetchColumn();
        $now = time();

        $followerValues = [];
        $tagFollowerValues = [];

        for ($i = 1; $i <= 50; ++$i) {
            // user_id: chuỗi số duy nhất (Zalo user_id dạng numeric, CHAR 30)
            $userId = (string) (rand(1000000000, 9999999999) . str_pad((string) $i, 4, '0', STR_PAD_LEFT));

            $name = $ho[array_rand($ho)] . ' ' . $dem[array_rand($dem)] . ' ' . $ten[array_rand($ten)];
            $displayName = $name;
            $gender = (string) rand(1, 2);
            $avatar = 'https://s120-ava-talk.zadn.vn/sample/' . $userId . '.jpg';
            $avatar240 = 'https://s240-ava-talk.zadn.vn/sample/' . $userId . '.jpg';

            // phone_code lưu theo khóa của $callingcodes (parse_phone trả $callingcodes2['84'][0] = 'VN84'),
            // KHÔNG phải số gọi '84' — nếu lưu '84' sẽ gây Undefined array key tại followers.php khi hiển thị.
            $phoneCode = 'VN84';
            $phoneNumber = '9' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            [$cityId, $districtId] = $cities[array_rand($cities)];
            $address = $cityId !== '' ? ('Số ' . rand(1, 200) . ' đường mẫu') : '';

            $weight = $maxWeight + $i;
            $updatetime = $now - rand(0, 90 * 86400);

            // ~50% follower được gán 1-2 nhãn
            $tagsInfo = '';
            if ($i % 2 === 0) {
                $shuffled = $tagAliases;
                shuffle($shuffled);
                $picked = array_slice($shuffled, 0, rand(1, 2));
                $tagsInfo = implode(',', $picked);

                foreach ($picked as $alias) {
                    $tagFollowerValues[] = sprintf("('%s','%s')", $esc($alias), $esc($userId));
                }
            }

            $followerValues[] = sprintf(
                "('%s','%s','%s','%s',0,'%s','%s','%s','%s','',1,%d,'%s','%s','%s','%s','%s','%s',1,%d)",
                $esc($userId),
                $esc($appId),
                $esc($userId), // user_id_by_app
                $esc($displayName),
                $esc($avatar),
                $esc($avatar240),
                $esc($gender),
                $esc($tagsInfo),
                $weight,
                $esc($name),
                $esc($phoneCode),
                $esc($phoneNumber),
                $esc($address),
                $esc($cityId),
                $esc($districtId),
                $updatetime
            );
        }

        $this->assertCount(50, $followerValues, 'Số follower mẫu tạo ra không đúng 50 bản ghi.');

        // Cột tags_info đặt trước notes_info(''); is_sync=1
        $db->exec(
            'INSERT IGNORE INTO ' . $followersTable
            . ' (user_id, app_id, user_id_by_app, display_name, is_sensitive, avatar120, avatar240,'
            . ' user_gender, tags_info, notes_info, isfollow, weight, name, phone_code, phone_number,'
            . ' address, city_id, district_id, is_sync, updatetime) VALUES '
            . implode(',', $followerValues)
        );

        // Gán nhãn vào bảng quan hệ để bộ lọc theo tag hoạt động
        if (!empty($tagFollowerValues)) {
            $db->exec(
                'INSERT IGNORE INTO ' . $tagsFollowerTable
                . ' (tag, user_id) VALUES ' . implode(',', $tagFollowerValues)
            );
        }

        $this->assertTrue(true);
    }

    /**
     * Dữ liệu mẫu hội thoại (conversation) cho màn quản trị followers của module zalo
     *
     * Với mỗi follower (tối đa 10) sinh 8-15 tin nhắn vào _zalo_conversation, xen kẽ
     * src=1 (người quan tâm gửi) và src=0 (OA gửi). Đa dạng type: text, photo, voice,
     * link, location, sticker để các nhánh hiển thị của conversation_to_html() đều có
     * dữ liệu. Phụ thuộc dữ liệu follower nên skip nếu _zalo_followers trống.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForZaloConversation()
    {
        global $db, $db_config;

        $followersTable = $db_config['prefix'] . '_zalo_followers';
        $conversationTable = $db_config['prefix'] . '_zalo_conversation';

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        // Conversation phụ thuộc follower có sẵn. Lấy đúng tập follower hiển thị trên trang
        // (cùng bộ lọc isfollow=1 + app_id, ORDER BY weight ASC như followers.php) để bất kỳ
        // follower nào mở ra cũng có hội thoại — tránh trường hợp chỉ vài follower có dữ liệu.
        $appId = (string) $db->query(
            "SELECT config_value FROM " . $db_config['prefix'] . "_config WHERE config_name = 'zaloAppID' LIMIT 1"
        )->fetchColumn();
        $sql = 'SELECT user_id FROM ' . $followersTable . ' WHERE isfollow = 1';
        if ($appId !== '') {
            $sql .= ' AND app_id = ' . $db->quote($appId);
        }
        $sql .= ' ORDER BY weight ASC LIMIT 50';
        $userIds = $db->query($sql)->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($userIds)) {
            $this->markTestSkipped('Chưa có follower trong ' . $followersTable . '; chạy testInsertSampleDataForZaloFollowers trước.');
        }

        // Tin nhắn dạng text mẫu (tiếng Việt) cho cả hai chiều OA <-> follower
        $textsFromUser = [
            'Chào shop, sản phẩm này còn hàng không ạ?',
            'Cho mình hỏi giá bao nhiêu vậy?',
            'Mình muốn đặt 2 cái, ship về Hà Nội nhé.',
            'Bao giờ thì giao hàng được ạ?',
            'Cảm ơn shop nhiều nha!',
            'Có size lớn hơn không shop?',
            'Mình chuyển khoản rồi nhé.',
            'Shop tư vấn giúp mình mẫu nào bền hơn với.',
        ];
        $textsFromOA = [
            'Dạ chào anh/chị, sản phẩm còn hàng ạ.',
            'Dạ giá sản phẩm là 350.000đ ạ.',
            'Dạ shop đã ghi nhận đơn, giao trong 2-3 ngày ạ.',
            'Dạ anh/chị cho shop xin số điện thoại để lên đơn ạ.',
            'Cảm ơn anh/chị đã ủng hộ shop ạ!',
            'Dạ bên em có đủ size từ S đến XXL ạ.',
            'Dạ shop đã nhận được thanh toán, cảm ơn anh/chị ạ.',
        ];

        // Sticker id mẫu của Zalo
        $stickerIds = ['46101', '46102', '46103', '46104'];

        $values = [];
        $now = time();
        $seq = 0;

        foreach ($userIds as $userId) {
            $msgCount = rand(8, 15);
            // Tin cũ nhất cách hiện tại tối đa ~7 ngày, mỗi tin cách nhau vài phút
            $baseTime = $now - rand(86400, 7 * 86400);

            for ($i = 0; $i < $msgCount; ++$i) {
                ++$seq;
                $src = $i % 2; // 1 = follower gửi, 0 = OA gửi
                $time = $baseTime + $i * rand(60, 600);
                // message_id tất định theo (user_id, i) để chạy lại seed không nhân đôi tin nhắn
                $messageId = sprintf('sample_conv_%s_%d', $userId, $i);

                $type = 'text';
                $message = '';
                $links = '';
                $thumb = '';
                $url = '';
                $description = '';
                $location = '';

                // Phần lớn là text; chèn xen vài loại đặc biệt theo vị trí
                if ($i === 2 && $src === 1) {
                    // Ảnh từ follower
                    $type = 'photo';
                    $url = 'https://f' . rand(1, 9) . '-zpg.zdn.vn/sample/photo_' . $seq . '.jpg';
                    $thumb = $url;
                    $description = 'Ảnh sản phẩm khách gửi';
                } elseif ($i === 4 && $src === 1) {
                    // Tin nhắn thoại từ follower (để hiện nút play trong giao diện)
                    $type = 'voice';
                    $url = 'https://sample-zalo-cdn.zadn.vn/voice/' . $seq . '.amr';
                } elseif ($i === 5 && $src === 0) {
                    // Link sản phẩm từ OA
                    $type = 'link';
                    $links = json_encode([[
                        'type' => 'link',
                        'url' => 'https://shop.example.com/sp/' . $seq,
                        'title' => 'Sản phẩm mẫu #' . $seq,
                        'thumb' => 'https://shop.example.com/thumb/' . $seq . '.jpg',
                        'description' => 'Mô tả ngắn sản phẩm mẫu số ' . $seq,
                    ]], NV_JSON_ENCODE);
                } elseif ($i === 6 && $src === 1) {
                    // Vị trí từ follower
                    $type = 'location';
                    $location = json_encode([
                        'latitude' => 21.027763 + (rand(-500, 500) / 10000),
                        'longitude' => 105.834160 + (rand(-500, 500) / 10000),
                    ], NV_JSON_ENCODE);
                } elseif ($i === 7) {
                    // Sticker
                    $type = 'sticker';
                    $url = 'https://zalo-api.zadn.vn/sticker/' . $stickerIds[array_rand($stickerIds)] . '.png';
                } else {
                    $message = $src === 1
                        ? $textsFromUser[array_rand($textsFromUser)]
                        : $textsFromOA[array_rand($textsFromOA)];
                }

                $values[] = sprintf(
                    "('%s','%s',%d,%d,'%s','%s','%s','%s','%s','%s','%s','',1)",
                    $esc($messageId),
                    $esc((string) $userId),
                    $src,
                    $time,
                    $esc($type),
                    $esc($message),
                    $esc($links),
                    $esc($thumb),
                    $esc($url),
                    $esc($description),
                    $esc($location)
                );
            }
        }

        $this->assertNotEmpty($values, 'Không sinh được tin nhắn hội thoại mẫu nào.');

        // displayed=1 (đã hiển thị), note='' — bulk insert, bỏ qua nếu trùng message_id
        $db->exec(
            'INSERT IGNORE INTO ' . $conversationTable
            . ' (message_id, user_id, src, time, type, message, links, thumb, url, description, location, note, displayed) VALUES '
            . implode(',', $values)
        );

        $this->assertTrue(true);
    }

    /**
     * Danh sách trường tùy biến mẫu cho module users
     *
     * Phủ đủ 10 field_type của cột enum trong _users_field và đủ 7 match_type,
     * mỗi loại đều có cả biến thể bắt buộc lẫn không bắt buộc để test form.
     * Toàn bộ đặt show_register = show_profile = user_editable = 1 nên xuất hiện
     * ở cả trang đăng ký lẫn trang thông tin tài khoản.
     *
     * Khóa của mỗi phần tử là tên cột sẽ thêm vào _users_info.
     *
     * @return array
     */
    private function sampleCustomFields(): array
    {
        $year = (int) date('Y');

        return [
            // textbox — không ràng buộc định dạng, bắt buộc
            'noi_cong_tac' => [
                'title' => 'Nơi công tác',
                'description' => 'Tên cơ quan hoặc doanh nghiệp bạn đang làm việc',
                'field_type' => 'textbox',
                'match_type' => 'none',
                'required' => 1,
                'min_length' => 5,
                'max_length' => 150,
            ],
            // textbox — alphanumeric, không bắt buộc
            'ma_nhan_vien' => [
                'title' => 'Mã nhân viên',
                'description' => 'Chỉ gồm chữ cái không dấu, chữ số và dấu gạch dưới',
                'field_type' => 'textbox',
                'match_type' => 'alphanumeric',
                'required' => 0,
                'min_length' => 3,
                'max_length' => 20,
            ],
            // textbox — unicodename, bắt buộc
            'nguoi_dai_dien' => [
                'title' => 'Người đại diện',
                'description' => 'Họ tên đầy đủ, có thể dùng chữ có dấu',
                'field_type' => 'textbox',
                'match_type' => 'unicodename',
                'required' => 1,
                'min_length' => 2,
                'max_length' => 100,
            ],
            // textbox — email, không bắt buộc
            'email_lien_he' => [
                'title' => 'Email liên hệ',
                'description' => 'Email dùng để liên hệ công việc, khác email đăng nhập',
                'field_type' => 'textbox',
                'match_type' => 'email',
                'required' => 0,
                'min_length' => 0,
                'max_length' => 100,
            ],
            // textbox — url, không bắt buộc
            'website_ca_nhan' => [
                'title' => 'Website cá nhân',
                'description' => 'Địa chỉ đầy đủ, ví dụ https://nukeviet.vn',
                'field_type' => 'textbox',
                'match_type' => 'url',
                'required' => 0,
                'min_length' => 0,
                'max_length' => 200,
            ],
            // textbox — regex, bắt buộc
            'so_dien_thoai' => [
                'title' => 'Số điện thoại',
                'description' => 'Số di động 10 chữ số, bắt đầu bằng 03, 05, 07, 08 hoặc 09',
                'field_type' => 'textbox',
                'match_type' => 'regex',
                'match_regex' => '/^0[35789][0-9]{8}$/',
                'required' => 1,
                'min_length' => 10,
                'max_length' => 10,
            ],
            // textbox — callback, không bắt buộc
            'ma_so_thue' => [
                'title' => 'Mã số thuế',
                'description' => 'Chỉ gồm chữ số, 10 hoặc 13 ký tự',
                'field_type' => 'textbox',
                'match_type' => 'callback',
                'func_callback' => 'ctype_digit',
                'required' => 0,
                'min_length' => 10,
                'max_length' => 13,
            ],
            // textarea — không bắt buộc
            'gioi_thieu' => [
                'title' => 'Giới thiệu bản thân',
                'description' => 'Mô tả ngắn gọn về bạn',
                'field_type' => 'textarea',
                'match_type' => 'none',
                'required' => 0,
                'min_length' => 0,
                'max_length' => 1000,
            ],
            // editor — bắt buộc, class lưu theo dạng rộng@cao
            'kinh_nghiem' => [
                'title' => 'Kinh nghiệm làm việc',
                'description' => 'Trình bày quá trình công tác, có định dạng',
                'field_type' => 'editor',
                'match_type' => 'none',
                'required' => 1,
                'min_length' => 20,
                'max_length' => 5000,
                'class' => '100%@300px',
            ],
            // number — số nguyên, bắt buộc
            'so_nam_kn' => [
                'title' => 'Số năm kinh nghiệm',
                'description' => 'Nhập số nguyên từ 0 đến 50',
                'field_type' => 'number',
                'match_type' => 'none',
                'required' => 1,
                'min_length' => 0,
                'max_length' => 50,
                'number_type' => 1,
                'default_value' => 1,
            ],
            // number — số thập phân, không bắt buộc
            'muc_luong' => [
                'title' => 'Mức lương mong muốn',
                'description' => 'Đơn vị triệu đồng, được nhập số lẻ',
                'field_type' => 'number',
                'match_type' => 'none',
                'required' => 0,
                'min_length' => 0,
                'max_length' => 500,
                'number_type' => 2,
                'default_value' => 0,
            ],
            // date — có giới hạn khoảng ngày, bắt buộc
            'ngay_vao_lam' => [
                'title' => 'Ngày vào làm',
                'description' => 'Chọn ngày bắt đầu công việc hiện tại',
                'field_type' => 'date',
                'match_type' => 'none',
                'required' => 1,
                'min_length' => mktime(0, 0, 0, 1, 1, 2000),
                'max_length' => mktime(0, 0, 0, 12, 31, $year + 1),
                'current_date' => 0,
            ],
            // date — mặc định là ngày hiện tại, không bắt buộc
            'ngay_cap_nhat' => [
                'title' => 'Ngày cập nhật hồ sơ',
                'description' => 'Mặc định lấy ngày hiện tại',
                'field_type' => 'date',
                'match_type' => 'none',
                'required' => 0,
                'min_length' => 0,
                'max_length' => 0,
                'current_date' => 1,
            ],
            // select — bắt buộc, mặc định chọn mục thứ 2
            'trinh_do' => [
                'title' => 'Trình độ học vấn',
                'description' => '',
                'field_type' => 'select',
                'match_type' => 'none',
                'required' => 1,
                'choices' => [
                    'thpt' => 'Trung học phổ thông',
                    'caodang' => 'Cao đẳng',
                    'daihoc' => 'Đại học',
                    'thacsi' => 'Thạc sĩ',
                    'tiensi' => 'Tiến sĩ',
                ],
                'default_value' => 3,
            ],
            // radio — không bắt buộc
            'hon_nhan' => [
                'title' => 'Tình trạng hôn nhân',
                'description' => '',
                'field_type' => 'radio',
                'match_type' => 'none',
                'required' => 0,
                'choices' => [
                    'docthan' => 'Độc thân',
                    'ketthon' => 'Đã kết hôn',
                    'khac' => 'Khác',
                ],
            ],
            // checkbox — không bắt buộc, chọn được nhiều mục
            'so_thich' => [
                'title' => 'Sở thích',
                'description' => 'Có thể chọn nhiều mục',
                'field_type' => 'checkbox',
                'match_type' => 'none',
                'required' => 0,
                'choices' => [
                    'docsach' => 'Đọc sách',
                    'thethao' => 'Thể thao',
                    'dulich' => 'Du lịch',
                    'amnhac' => 'Âm nhạc',
                    'nauan' => 'Nấu ăn',
                ],
            ],
            // multiselect — bắt buộc, chọn được nhiều mục
            'ky_nang' => [
                'title' => 'Kỹ năng chuyên môn',
                'description' => 'Giữ Ctrl để chọn nhiều mục',
                'field_type' => 'multiselect',
                'match_type' => 'none',
                'required' => 1,
                'choices' => [
                    'php' => 'PHP',
                    'javascript' => 'JavaScript',
                    'mysql' => 'MySQL',
                    'linux' => 'Linux',
                    'docker' => 'Docker',
                ],
            ],
            // file — chỉ nhận ảnh, 1 tệp, bắt buộc
            'anh_chan_dung' => [
                'title' => 'Ảnh chân dung',
                'description' => 'Ảnh nền trắng, tối đa 1 tệp',
                'field_type' => 'file',
                'match_type' => 'none',
                'required' => 1,
                'filetype' => ['images'],
                'mime' => ['png', 'jpg', 'jpeg', 'webp'],
                'maxnum' => 1,
                'widthlimit' => ['equal' => 0, 'greater' => 200, 'less' => 0],
                'heightlimit' => ['equal' => 0, 'greater' => 200, 'less' => 0],
            ],
            // file — nhận tài liệu, nhiều tệp, không bắt buộc
            'tai_lieu' => [
                'title' => 'Tài liệu đính kèm',
                'description' => 'Bằng cấp, chứng chỉ, tối đa 5 tệp',
                'field_type' => 'file',
                'match_type' => 'none',
                'required' => 0,
                'filetype' => ['documents', 'adobe'],
                'mime' => ['pdf', 'doc', 'docx'],
                'maxnum' => 5,
            ],
        ];
    }

    /**
     * Dữ liệu mẫu trường tùy biến (custom field) cho module users
     *
     * Tạo bộ trường trong _users_field rồi thêm cột tương ứng vào _users_info,
     * mô phỏng đúng những gì admin/fields.php sinh ra khi thêm trường qua giao diện.
     * Trường đã tồn tại sẽ được bỏ qua nên chạy lại nhiều lần vẫn an toàn.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersCustomFields()
    {
        global $db, $db_config;

        $fieldTable = $db_config['prefix'] . '_users_field';
        $infoTable = $db_config['prefix'] . '_users_info';

        // Trường và cột đã có, dùng để bỏ qua khi chạy lại
        $existingFields = $db->query('SELECT field FROM ' . $fieldTable)->fetchAll(\PDO::FETCH_COLUMN);
        $existingColumns = $db->query('SHOW COLUMNS FROM ' . $infoTable)->fetchAll(\PDO::FETCH_COLUMN);

        $weight = (int) $db->query('SELECT MAX(weight) FROM ' . $fieldTable)->fetchColumn();

        $created = 0;

        foreach ($this->sampleCustomFields() as $field => $spec) {
            if (in_array($field, $existingFields, true)) {
                continue;
            }

            $type = $spec['field_type'];
            $minLength = (int) ($spec['min_length'] ?? 0);
            $maxLength = (int) ($spec['max_length'] ?? 0);
            $fieldChoices = '';
            $limitedValues = '';
            $defaultValue = '';

            if ($type == 'number') {
                $fieldChoices = serialize(['number_type' => (int) $spec['number_type']]);
                $defaultValue = (string) ($spec['default_value'] ?? 0);
            } elseif ($type == 'date') {
                $fieldChoices = serialize(['current_date' => (int) $spec['current_date']]);
                // current_date = 1 thì giá trị mặc định luôn là thời điểm hiện tại
                $defaultValue = '0';
            } elseif ($type == 'file') {
                $limitedValues = json_encode([
                    'filetype' => $spec['filetype'],
                    'mime' => $spec['mime'],
                    'file_max_size' => 41943040,
                    'maxnum' => (int) $spec['maxnum'],
                    'widthlimit' => $spec['widthlimit'] ?? ['equal' => 0, 'greater' => 0, 'less' => 0],
                    'heightlimit' => $spec['heightlimit'] ?? ['equal' => 0, 'greater' => 0, 'less' => 0],
                ], JSON_UNESCAPED_UNICODE);
            } elseif (!empty($spec['choices'])) {
                // Nhóm chọn lưu nhãn theo ngôn ngữ, min/max giống hệt admin sinh ra
                $choices = [];
                foreach ($spec['choices'] as $key => $label) {
                    $choices[$key] = [NV_LANG_DATA => $label];
                }
                $fieldChoices = serialize($choices);
                $minLength = 0;
                $maxLength = 255;
                $defaultValue = (string) ($spec['default_value'] ?? 0);
            } else {
                // Nhóm nhập chữ lưu giá trị mặc định theo ngôn ngữ dưới dạng JSON
                $defaultValue = json_encode([NV_LANG_DATA => ''], JSON_UNESCAPED_UNICODE);
            }

            $stmt = $db->prepare('INSERT INTO ' . $fieldTable . ' (
                field, weight, field_type, field_choices, sql_choices, match_type,
                match_regex, func_callback, min_length, max_length, limited_values,
                for_admin, required, show_register, user_editable,
                show_profile, class, language, default_value, is_system
            ) VALUES (
                :field, :weight, :field_type, :field_choices, \'\', :match_type,
                :match_regex, :func_callback, :min_length, :max_length, :limited_values,
                0, :required, 1, 1,
                1, :class, :language, :default_value, 0
            )');

            $stmt->bindValue(':field', $field, \PDO::PARAM_STR);
            $stmt->bindValue(':weight', ++$weight, \PDO::PARAM_INT);
            $stmt->bindValue(':field_type', $type, \PDO::PARAM_STR);
            $stmt->bindValue(':field_choices', $fieldChoices, \PDO::PARAM_STR);
            $stmt->bindValue(':match_type', $spec['match_type'], \PDO::PARAM_STR);
            $stmt->bindValue(':match_regex', $spec['match_regex'] ?? '', \PDO::PARAM_STR);
            $stmt->bindValue(':func_callback', $spec['func_callback'] ?? '', \PDO::PARAM_STR);
            $stmt->bindValue(':min_length', $minLength, \PDO::PARAM_INT);
            $stmt->bindValue(':max_length', $maxLength, \PDO::PARAM_INT);
            $stmt->bindValue(':limited_values', $limitedValues, \PDO::PARAM_STR);
            $stmt->bindValue(':required', (int) $spec['required'], \PDO::PARAM_INT);
            $stmt->bindValue(':class', $spec['class'] ?? 'input', \PDO::PARAM_STR);
            $stmt->bindValue(':language', serialize([NV_LANG_DATA => [$spec['title'], $spec['description']]]), \PDO::PARAM_STR);
            $stmt->bindValue(':default_value', $defaultValue, \PDO::PARAM_STR);
            $stmt->execute();

            // Thêm cột vào _users_info, kiểu cột suy ra giống admin/fields.php
            if (!in_array($field, $existingColumns, true)) {
                if ($type == 'number' or $type == 'date') {
                    $columnType = "DOUBLE NOT NULL DEFAULT '" . (float) $defaultValue . "'";
                } elseif ($type == 'file' or $maxLength > 65536) {
                    $columnType = 'TEXT NOT NULL';
                } elseif ($maxLength <= 255) {
                    $columnType = 'VARCHAR(' . $maxLength . ") NOT NULL DEFAULT ''";
                } else {
                    $columnType = 'TEXT NOT NULL';
                }

                $db->query('ALTER TABLE ' . $infoTable . ' ADD ' . $field . ' ' . $columnType
                    . ' COMMENT ' . $db->quote($spec['title']));
            }

            ++$created;
        }

        $total = (int) $db->query('SELECT COUNT(*) FROM ' . $fieldTable . ' WHERE is_system = 0')->fetchColumn();

        $this->assertGreaterThan(0, $total, 'Không có trường tùy biến nào trong ' . $fieldTable);
        $this->assertTrue($created >= 0);
    }

    /**
     * Dữ liệu mẫu giá trị các trường tùy biến trong _users_info
     *
     * Điền giá trị hợp lệ cho mọi user hiện có để trang thông tin tài khoản và
     * trang sửa thông tin có dữ liệu hiển thị. Giá trị sinh ra tôn trọng đúng
     * ràng buộc của từng trường (regex, khoảng số, khoảng ngày, danh sách chọn).
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersCustomFieldValues()
    {
        global $db, $db_config;

        $fieldTable = $db_config['prefix'] . '_users_field';
        $infoTable = $db_config['prefix'] . '_users_info';

        $users = $db->query('SELECT userid FROM ' . $db_config['prefix'] . '_users ORDER BY userid ASC')
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($users)) {
            $this->markTestSkipped('Không có user nào trong bảng ' . $db_config['prefix'] . '_users.');
        }

        // Chỉ điền cho các trường mẫu do test này tạo và đang thực sự có cột
        $samples = $this->sampleCustomFields();
        $fields = $db->query('SELECT field FROM ' . $fieldTable . ' WHERE is_system = 0')
            ->fetchAll(\PDO::FETCH_COLUMN);
        $columns = $db->query('SHOW COLUMNS FROM ' . $infoTable)->fetchAll(\PDO::FETCH_COLUMN);
        $fields = array_intersect($fields, array_keys($samples), $columns);

        if (empty($fields)) {
            $this->markTestSkipped('Chưa có trường tùy biến mẫu nào, chạy testInsertSampleDataForUsersCustomFields trước.');
        }

        $congTy = ['Công ty CP VINADES', 'Trung tâm CNTT Hà Nội', 'Đại học Bách khoa', 'Công ty TNHH Ánh Dương'];
        $hoTen = ['Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Ngọc Dung'];
        $gioiThieu = [
            'Lập trình viên PHP với nhiều năm gắn bó cùng mã nguồn mở NukeViet.',
            'Quản trị hệ thống, quan tâm tới bảo mật và tối ưu hiệu năng.',
            'Chuyên viên phân tích nghiệp vụ, yêu thích công việc với dữ liệu.',
        ];
        $kinhNghiem = [
            '<p>2019 - 2022: Lập trình viên tại <strong>VINADES</strong>, phát triển module cho NukeViet.</p><p>2022 - nay: Trưởng nhóm kỹ thuật.</p>',
            '<p>2020 - nay: Quản trị hệ thống máy chủ Linux, triển khai CI/CD cho các dự án nội bộ.</p>',
        ];

        $updated = 0;

        foreach ($users as $index => $userid) {
            $userid = (int) $userid;
            $data = [];

            foreach ($fields as $field) {
                $spec = $samples[$field];
                $type = $spec['field_type'];

                if ($type == 'file') {
                    // Không sinh tệp thật, để rỗng cho an toàn
                    $data[$field] = '';
                } elseif ($type == 'number') {
                    $data[$field] = ((int) $spec['number_type'] == 1)
                        ? (string) rand((int) $spec['min_length'], (int) $spec['max_length'])
                        : (string) round(rand((int) $spec['min_length'] * 100, (int) $spec['max_length'] * 100) / 100, 2);
                } elseif ($type == 'date') {
                    $min = (int) $spec['min_length'];
                    $max = (int) $spec['max_length'];
                    // Trường không giới hạn khoảng thì lấy quanh thời điểm hiện tại
                    $data[$field] = ($min > 0 and $max > $min)
                        ? (string) rand($min, $max)
                        : (string) (NV_CURRENTTIME - rand(0, 86400 * 365));
                } elseif (!empty($spec['choices'])) {
                    $keys = array_keys($spec['choices']);
                    if ($type == 'checkbox' or $type == 'multiselect') {
                        shuffle($keys);
                        $data[$field] = implode(',', array_slice($keys, 0, rand(1, min(3, count($keys)))));
                    } else {
                        $data[$field] = $keys[array_rand($keys)];
                    }
                } elseif ($spec['match_type'] == 'alphanumeric') {
                    $data[$field] = 'NV' . str_pad((string) ($userid + 1000), 5, '0', STR_PAD_LEFT);
                } elseif ($spec['match_type'] == 'unicodename') {
                    $data[$field] = $hoTen[$index % count($hoTen)];
                } elseif ($spec['match_type'] == 'email') {
                    $data[$field] = 'lienhe' . $userid . '@nukeviet.vn';
                } elseif ($spec['match_type'] == 'url') {
                    $data[$field] = 'https://nukeviet.vn/thanh-vien-' . $userid;
                } elseif ($spec['match_type'] == 'regex') {
                    // Khớp /^0[35789][0-9]{8}$/
                    $data[$field] = '09' . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                } elseif ($spec['match_type'] == 'callback') {
                    // ctype_digit, độ dài trong khoảng min/max
                    $data[$field] = str_pad((string) rand(0, 999999999), (int) $spec['min_length'], '0', STR_PAD_LEFT);
                } elseif ($type == 'editor') {
                    $data[$field] = $kinhNghiem[$index % count($kinhNghiem)];
                } elseif ($type == 'textarea') {
                    $data[$field] = $gioiThieu[$index % count($gioiThieu)];
                } else {
                    $data[$field] = $congTy[$index % count($congTy)];
                }
            }

            $assign = [];
            foreach (array_keys($data) as $field) {
                $assign[] = $field . ' = :' . $field;
            }

            // User có thể chưa có dòng trong _users_info nên chèn trước rồi mới cập nhật
            $db->exec('INSERT IGNORE INTO ' . $infoTable . ' (userid) VALUES (' . $userid . ')');

            $stmt = $db->prepare('UPDATE ' . $infoTable . ' SET ' . implode(', ', $assign) . ' WHERE userid = ' . $userid);
            foreach ($data as $field => $value) {
                $stmt->bindValue(':' . $field, $value, \PDO::PARAM_STR);
            }
            $stmt->execute();

            ++$updated;
        }

        $this->assertGreaterThan(0, $updated, 'Không cập nhật được dòng nào trong ' . $infoTable);
    }

    /**
     * Dữ liệu mẫu 100 tài khoản và phân bổ vào nhóm group_id = 10
     *
     * Sinh 100 user sample_user_001..sample_user_100 rồi đưa vào nhóm 10 với đủ
     * ba trạng thái: trưởng nhóm, thành viên đã duyệt và thành viên chờ duyệt.
     * Trạng thái gán theo thứ tự cố định nên chạy lại nhiều lần không tạo dữ liệu
     * mâu thuẫn, kết hợp INSERT IGNORE và các khóa UNIQUE để không nhân bản.
     *
     * Mật khẩu chung của mọi tài khoản mẫu: SampleUser@123
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersGroups()
    {
        global $db, $db_config, $global_config;

        $groupId = 10;
        $total = 100;
        $numLeader = 2;   // is_leader = 1, approved = 1
        $numMember = 80;  // is_leader = 0, approved = 1
        // Số còn lại (18) ở trạng thái chờ duyệt: approved = 0

        $userTable = $db_config['prefix'] . '_users';
        $infoTable = $db_config['prefix'] . '_users_info';
        $groupTable = $db_config['prefix'] . '_users_groups';
        $groupUserTable = $db_config['prefix'] . '_users_groups_users';

        // Nhóm đích phải tồn tại, không thì bỏ qua để tránh sinh dữ liệu mồ côi
        $exists = $db->query('SELECT COUNT(*) FROM ' . $groupTable . ' WHERE group_id = ' . $groupId)->fetchColumn();
        if (!$exists) {
            $this->markTestSkipped('Không tìm thấy nhóm group_id = ' . $groupId . ' trong bảng ' . $groupTable . '.');
        }

        $idsite = (int) ($global_config['idsite'] ?? 0);
        $crypt = new \NukeViet\Core\Encryption($global_config['sitekey']);
        $password = $crypt->hash_password('SampleUser@123', $global_config['hashprefix']);

        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Vũ', 'Đặng', 'Bùi', 'Đỗ', 'Ngô'];
        $dem = ['Văn', 'Thị', 'Hoàng', 'Ngọc', 'Minh', 'Thanh', 'Quang', 'Hữu'];
        $ten = ['An', 'Bình', 'Cường', 'Dung', 'Giang', 'Hà', 'Khánh', 'Linh', 'Nam', 'Oanh', 'Phúc', 'Quân', 'Sơn', 'Trang', 'Vinh', 'Yến'];
        $genders = ['M', 'F', 'N'];

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        // Trạng thái trong nhóm gán theo thứ tự: 2 trưởng nhóm, 80 thành viên, phần còn lại chờ duyệt
        $statusOf = function (int $index) use ($numLeader, $numMember): string {
            if ($index <= $numLeader) {
                return 'leader';
            }

            return ($index <= $numLeader + $numMember) ? 'member' : 'pending';
        };

        $usernames = [];
        $values = [];

        for ($i = 1; $i <= $total; $i++) {
            $seq = str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $username = 'sample_user_' . $seq;
            $usernames[$i] = $username;

            // Chờ duyệt thì chưa được tính vào nhóm 10
            $inGroups = ($statusOf($i) == 'pending') ? '4' : '4,' . $groupId;

            // regdate rải trong 2 năm gần đây, birthday trong khoảng 1975-2005
            $regdate = NV_CURRENTTIME - rand(0, 86400 * 730);
            $birthday = mktime(0, 0, 0, rand(1, 12), rand(1, 28), rand(1975, 2005));

            $values[] = sprintf(
                "(4, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, %d, '', '', %d, 1, '%s', 1, %d, %d, %d, %d, -1, 'SYSTEM', '')",
                $esc($username),
                md5(strtolower($username)), // nv_md5safe(), username thuần ASCII nên không cần nv_strtolower()
                $esc($password),
                $esc('sample.user.' . $seq . '@example.com'),
                $esc($dem[array_rand($dem)] . ' ' . $ten[array_rand($ten)]),
                $esc($ho[array_rand($ho)]),
                $genders[array_rand($genders)],
                $birthday,
                $regdate,
                rand(0, 1),
                $inGroups,
                $idsite,
                $regdate,
                $regdate,
                $regdate
            );
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $userTable . ' ('
            . 'group_id, username, md5username, password, email, first_name, last_name, gender,'
            . ' birthday, regdate, question, answer, view_mail, remember, in_groups, active,'
            . ' idsite, pass_creation_time, last_update, email_creation_time, email_verification_time,'
            . ' active_obj, language'
            . ') VALUES ' . implode(',', $values)
        );

        // Lấy lại userid thật, kể cả các tài khoản đã tồn tại từ lần chạy trước
        $stmt = $db->prepare('SELECT username, userid FROM ' . $userTable . ' WHERE username LIKE :prefix');
        $stmt->bindValue(':prefix', 'sample\_user\_%', \PDO::PARAM_STR);
        $stmt->execute();
        $userids = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        $rows = [];
        $infoRows = [];

        foreach ($usernames as $index => $username) {
            if (!isset($userids[$username])) {
                continue;
            }

            $userid = (int) $userids[$username];
            $status = $statusOf($index);
            $requested = NV_CURRENTTIME - rand(86400, 86400 * 180);

            $rows[] = sprintf(
                "(%d, %d, %d, %d, '%d', %d, %d)",
                $groupId,
                $userid,
                $status == 'leader' ? 1 : 0,
                $status == 'pending' ? 0 : 1,
                $idsite,
                $requested,
                $status == 'pending' ? 0 : $requested
            );
            $infoRows[] = '(' . $userid . ')';
        }

        if (empty($rows)) {
            $this->markTestSkipped('Không lấy được userid của các tài khoản mẫu vừa tạo.');
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $groupUserTable
            . ' (group_id, userid, is_leader, approved, data, time_requested, time_approved)'
            . ' VALUES ' . implode(',', $rows)
        );

        // Bảo đảm mỗi tài khoản mẫu đều có dòng trong bảng thông tin mở rộng
        $db->exec('INSERT IGNORE INTO ' . $infoTable . ' (userid) VALUES ' . implode(',', $infoRows));

        // Tính lại numbers từ dữ liệu thật thay vì cộng dồn, tránh lệch khi chạy lại
        $db->exec(
            'UPDATE ' . $groupTable . ' SET numbers = ('
            . 'SELECT COUNT(*) FROM ' . $groupUserTable . ' WHERE group_id = ' . $groupId . ' AND approved = 1'
            . ') WHERE group_id = ' . $groupId
        );
        // Nhóm 4 (thành viên chính thức) đếm theo in_groups, không theo cột group_id
        $db->exec(
            'UPDATE ' . $groupTable . ' SET numbers = ('
            . "SELECT COUNT(*) FROM " . $userTable . " WHERE FIND_IN_SET('4', in_groups)"
            . ') WHERE group_id = 4'
        );

        $inserted = $db->query(
            'SELECT COUNT(*) FROM ' . $groupUserTable . ' WHERE group_id = ' . $groupId
        )->fetchColumn();

        $this->assertGreaterThanOrEqual($total, (int) $inserted, 'Nhóm ' . $groupId . ' chưa đủ ' . $total . ' thành viên mẫu.');
    }

    /**
     * Sinh giá trị hợp lệ cho các trường tùy biến của module users
     *
     * Giá trị tôn trọng đúng ràng buộc khai báo trong sampleCustomFields():
     * regex, khoảng số, khoảng ngày và danh sách lựa chọn.
     *
     * @param array $fields Tên các trường cần sinh giá trị
     * @param int   $index  Số thứ tự bản ghi, dùng để xoay vòng dữ liệu mẫu
     * @return array Mảng tên trường => giá trị dạng chuỗi
     */
    private function sampleUserInfoValues(array $fields, int $index): array
    {
        $samples = $this->sampleCustomFields();

        $congTy = ['Công ty CP VINADES', 'Trung tâm CNTT Hà Nội', 'Đại học Bách khoa', 'Công ty TNHH Ánh Dương'];
        $hoTen = ['Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Ngọc Dung'];
        $gioiThieu = [
            'Lập trình viên PHP với nhiều năm gắn bó cùng mã nguồn mở NukeViet.',
            'Quản trị hệ thống, quan tâm tới bảo mật và tối ưu hiệu năng.',
            'Chuyên viên phân tích nghiệp vụ, yêu thích công việc với dữ liệu.',
        ];
        $kinhNghiem = [
            '<p>2019 - 2022: Lập trình viên tại <strong>VINADES</strong>, phát triển module cho NukeViet.</p><p>2022 - nay: Trưởng nhóm kỹ thuật.</p>',
            '<p>2020 - nay: Quản trị hệ thống máy chủ Linux, triển khai CI/CD cho các dự án nội bộ.</p>',
        ];

        $data = [];

        foreach ($fields as $field) {
            if (!isset($samples[$field])) {
                continue;
            }

            $spec = $samples[$field];
            $type = $spec['field_type'];

            if ($type == 'file') {
                // Không sinh tệp thật, để rỗng cho an toàn
                $data[$field] = '';
            } elseif ($type == 'number') {
                $data[$field] = ((int) $spec['number_type'] == 1)
                    ? (string) rand((int) $spec['min_length'], (int) $spec['max_length'])
                    : (string) round(rand((int) $spec['min_length'] * 100, (int) $spec['max_length'] * 100) / 100, 2);
            } elseif ($type == 'date') {
                $min = (int) $spec['min_length'];
                $max = (int) $spec['max_length'];
                // Trường không giới hạn khoảng thì lấy quanh thời điểm hiện tại
                $data[$field] = ($min > 0 and $max > $min)
                    ? (string) rand($min, $max)
                    : (string) (NV_CURRENTTIME - rand(0, 86400 * 365));
            } elseif (!empty($spec['choices'])) {
                $keys = array_keys($spec['choices']);
                if ($type == 'checkbox' or $type == 'multiselect') {
                    shuffle($keys);
                    $data[$field] = implode(',', array_slice($keys, 0, rand(1, min(3, count($keys)))));
                } else {
                    $data[$field] = $keys[array_rand($keys)];
                }
            } elseif ($spec['match_type'] == 'alphanumeric') {
                $data[$field] = 'NV' . str_pad((string) ($index + 1000), 5, '0', STR_PAD_LEFT);
            } elseif ($spec['match_type'] == 'unicodename') {
                $data[$field] = $hoTen[$index % count($hoTen)];
            } elseif ($spec['match_type'] == 'email') {
                $data[$field] = 'lienhe' . $index . '@nukeviet.vn';
            } elseif ($spec['match_type'] == 'url') {
                $data[$field] = 'https://nukeviet.vn/thanh-vien-' . $index;
            } elseif ($spec['match_type'] == 'regex') {
                // Khớp /^0[35789][0-9]{8}$/
                $data[$field] = '09' . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            } elseif ($spec['match_type'] == 'callback') {
                // ctype_digit, độ dài trong khoảng min/max
                $data[$field] = str_pad((string) rand(0, 999999999), (int) $spec['min_length'], '0', STR_PAD_LEFT);
            } elseif ($type == 'editor') {
                $data[$field] = $kinhNghiem[$index % count($kinhNghiem)];
            } elseif ($type == 'textarea') {
                $data[$field] = $gioiThieu[$index % count($gioiThieu)];
            } else {
                $data[$field] = $congTy[$index % count($congTy)];
            }
        }

        return $data;
    }

    /**
     * Dữ liệu mẫu tài khoản đăng ký đang chờ kích hoạt trong _users_reg
     *
     * Sinh 30 tài khoản sample_reg_NNN nằm ở bảng chờ, phục vụ test khối
     * getuserid của users/groups.php: xem danh sách chờ, tìm kiếm và kích hoạt.
     * Cột users_info chứa sẵn giá trị hợp lệ cho mọi trường tùy biến nên khi
     * trưởng nhóm bấm kích hoạt, userInfoTabDb() ghi được và tài khoản vừa tạo
     * không bị xóa ngược lại. Cứ 5 tài khoản thì 1 tài khoản có openid_info để
     * chạm luôn nhánh ghi bảng _users_openid.
     *
     * Mỗi lần chạy đều cấp dải số thứ tự mới, tính cả những tài khoản đã kích
     * hoạt và chuyển sang _users, nên không đụng khóa UNIQUE của cả hai bảng.
     *
     * regdate rải trong cửa sổ register_active_time còn hiệu lực, vì
     * delOldRegAccount() xóa hết tài khoản chờ quá hạn mỗi khi mở trang chờ
     * kích hoạt. Cấu hình đó càng ngắn thì dữ liệu mẫu càng sớm hết hạn, muốn
     * giữ lâu thì tăng "Thời gian tài khoản chờ kích hoạt" bên admin users.
     *
     * Mật khẩu chung của mọi tài khoản mẫu: SampleUser@123
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersRegWaiting()
    {
        global $db, $db_config, $global_config;

        $total = 30;
        $prefix = 'sample_reg_';

        $regTable = $db_config['prefix'] . '_users_reg';
        $userTable = $db_config['prefix'] . '_users';
        $infoTable = $db_config['prefix'] . '_users_info';
        $fieldTable = $db_config['prefix'] . '_users_field';
        $configTable = $db_config['prefix'] . '_users_config';

        // Số thứ tự lớn nhất đã dùng, tính cả tài khoản đã kích hoạt nằm ở _users
        $likePrefix = str_replace('_', '\_', $prefix) . '%';
        $maxSeq = 0;
        foreach ([$regTable, $userTable] as $table) {
            $stmt = $db->prepare('SELECT username FROM ' . $table . ' WHERE username LIKE :prefix');
            $stmt->bindValue(':prefix', $likePrefix, \PDO::PARAM_STR);
            $stmt->execute();
            foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $username) {
                $seq = (int) substr($username, strlen($prefix));
                if ($seq > $maxSeq) {
                    $maxSeq = $seq;
                }
            }
        }

        // Chỉ điền những trường tùy biến mẫu đang thực sự có cột trong _users_info
        $fields = $db->query('SELECT field FROM ' . $fieldTable . ' WHERE is_system = 0')
            ->fetchAll(\PDO::FETCH_COLUMN);
        $columns = $db->query('SHOW COLUMNS FROM ' . $infoTable)->fetchAll(\PDO::FETCH_COLUMN);
        $fields = array_values(array_intersect($fields, array_keys($this->sampleCustomFields()), $columns));

        // delOldRegAccount() xóa mọi dòng có regdate < NV_CURRENTTIME - register_active_time,
        // nên regdate phải nằm trong cửa sổ còn hiệu lực, không thì dữ liệu mẫu bay sạch ngay
        // lần đầu mở trang chờ kích hoạt bên admin. Chỉ dùng 80% cửa sổ để còn thời gian test.
        $activeTime = (int) $db->query(
            'SELECT content FROM ' . $configTable . " WHERE config = 'register_active_time'"
        )->fetchColumn();
        // Giá trị 0 nghĩa là không tự xóa, khi đó rải thoải mái trong 30 ngày cho giống thật
        $regRange = ($activeTime > 0) ? (int) ($activeTime * 0.8) : 86400 * 30;

        $idsite = (int) ($global_config['idsite'] ?? 0);
        $crypt = new \NukeViet\Core\Encryption($global_config['sitekey']);
        $password = $crypt->hash_password('SampleUser@123', $global_config['hashprefix']);

        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Vũ', 'Đặng', 'Bùi', 'Đỗ', 'Ngô'];
        $dem = ['Văn', 'Thị', 'Hoàng', 'Ngọc', 'Minh', 'Thanh', 'Quang', 'Hữu'];
        $ten = ['An', 'Bình', 'Cường', 'Dung', 'Giang', 'Hà', 'Khánh', 'Linh', 'Nam', 'Oanh', 'Phúc', 'Quân', 'Sơn', 'Trang', 'Vinh', 'Yến'];
        $genders = ['M', 'F', 'N'];
        $questions = ['Món ăn bạn thích nhất?', 'Tên trường cấp ba của bạn?', 'Thành phố bạn sinh ra?'];
        $answers = ['Phở bò', 'Chu Văn An', 'Hà Nội'];

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);

        $values = [];

        for ($i = 1; $i <= $total; $i++) {
            $seq = str_pad((string) ($maxSeq + $i), 3, '0', STR_PAD_LEFT);
            $username = $prefix . $seq;
            $email = 'sample.reg.' . $seq . '@example.com';

            $firstName = $dem[array_rand($dem)] . ' ' . $ten[array_rand($ten)];
            $lastName = $ho[array_rand($ho)];
            $gender = $genders[array_rand($genders)];

            $regdate = NV_CURRENTTIME - rand(0, $regRange);
            $birthday = mktime(0, 0, 0, rand(1, 12), rand(1, 28), rand(1975, 2005));
            $qIndex = $i % count($questions);

            // Cứ 5 tài khoản thì 1 tài khoản mô phỏng đăng ký qua OAuth. Bộ key phải khớp
            // set_reg_attribs() trong users/funcs/login.php, vì luồng kích hoạt lấy 'server'
            // cho cột openid và 'openid' cho cột id của bảng _users_openid, thiếu key nào là
            // câu INSERT đó hỏng. Để photo rỗng cho lúc kích hoạt khỏi tải ảnh từ Internet.
            $openidInfo = '';
            if ($i % 5 == 0) {
                $rawOpenid = (string) rand(1000000000, 2147483647) . rand(100000000, 999999999);
                $openidInfo = json_encode([
                    'server' => 'google',
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $gender,
                    'openid' => $rawOpenid,
                    'photo' => '',
                    'opid' => $crypt->hash($rawOpenid),
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $usersInfo = json_encode(
                $this->sampleUserInfoValues($fields, $i),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            $values[] = sprintf(
                "('%s','%s','%s','%s','%s','%s','%s',%d,'',%d,'%s','%s','%s','%s','%s',%d,0)",
                $esc($username),
                md5(strtolower($username)), // nv_md5safe(), username thuần ASCII nên không cần nv_strtolower()
                $esc($password),
                $esc($email),
                $esc($firstName),
                $esc($lastName),
                $gender,
                $birthday,
                $regdate,
                $esc($questions[$qIndex]),
                $esc($answers[$qIndex]),
                md5($username . $regdate), // checknum, mô phỏng mã trong link kích hoạt
                $esc((string) $usersInfo),
                $esc($openidInfo),
                $idsite
            );
        }

        $db->exec(
            'INSERT IGNORE INTO ' . $regTable . ' ('
            . 'username, md5username, password, email, first_name, last_name, gender,'
            . ' birthday, sig, regdate, question, answer, checknum, users_info, openid_info,'
            . ' idsite, lostactivelink'
            . ') VALUES ' . implode(',', $values)
        );

        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . $regTable . ' WHERE username LIKE :prefix');
        $stmt->bindValue(':prefix', $likePrefix, \PDO::PARAM_STR);
        $stmt->execute();
        $waiting = (int) $stmt->fetchColumn();

        $this->assertGreaterThanOrEqual($total, $waiting, 'Bảng ' . $regTable . ' chưa đủ ' . $total . ' tài khoản chờ kích hoạt.');
    }

    /**
     * Dữ liệu mẫu thông báo của nhóm cho trang users/groups/ID/inform ngoài site
     *
     * Trang đó chỉ là khung, danh sách nạp qua ajax từ inform/funcs/main.php với
     * filter=active và phân trang 20 dòng mỗi trang, nên phải có tối thiểu 41 dòng
     * active mới ra 3 trang. Hàm sinh 50 dòng active, kèm ít dòng waiting và expired
     * để hai tab lọc còn lại cũng có dữ liệu.
     *
     * Chạy lại nhiều lần không nhân bản dữ liệu: mỗi trạng thái mang một dấu seed cố
     * định, hàm đếm số dòng đang có đúng theo điều kiện lọc của module rồi chỉ bù
     * phần còn thiếu. Dòng active để exp_time = 0 hoặc hạn rất xa nên không tự hết
     * hạn giữa các lần chạy, chạy lại ngay sau đó sẽ không thêm dòng nào.
     *
     * @group sample-data
     */
    public function testInsertSampleDataForUsersGroupInform()
    {
        global $db, $db_config;

        $groupId = 10;
        $perPage = 20; // Trùng với $per_page trong inform/funcs/main.php
        $targets = [
            'active' => 50, // 50 dòng cho ra 3 trang
            'waiting' => 8,
            'expired' => 8,
        ];

        $informTable = $db_config['prefix'] . '_inform';
        $statusTable = $db_config['prefix'] . '_inform_status';
        $groupTable = $db_config['prefix'] . '_users_groups';
        $groupUserTable = $db_config['prefix'] . '_users_groups_users';

        // Nhóm đích phải tồn tại, không thì bỏ qua để tránh sinh dữ liệu mồ côi
        $exists = $db->query('SELECT COUNT(*) FROM ' . $groupTable . ' WHERE group_id = ' . $groupId)->fetchColumn();
        if (!$exists) {
            $this->markTestSkipped('Không tìm thấy nhóm group_id = ' . $groupId . ' trong bảng ' . $groupTable . '.');
        }

        // Người nhận phải là thành viên nhóm thì userlist_by_ids() bên inform mới hiện được tên
        $memberIds = $db->query(
            'SELECT userid FROM ' . $groupUserTable
            . ' WHERE group_id = ' . $groupId . ' AND approved = 1 ORDER BY userid ASC LIMIT 30'
        )->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($memberIds)) {
            $this->markTestSkipped('Nhóm ' . $groupId . ' chưa có thành viên nào đã duyệt.');
        }

        // Điều kiện lọc lấy nguyên theo inform/funcs/main.php để đếm đúng cái module sẽ hiển thị
        $stateWhere = [
            'active' => '(add_time <= :now1 AND (exp_time = 0 OR exp_time > :now2))',
            'waiting' => '(add_time > :now1)',
            'expired' => '(exp_time != 0 AND exp_time < :now1)',
        ];

        $esc = fn (string $s) => str_replace(["\\", "'"], ["\\\\", "\\'"], $s);
        $now = time();
        $memberCount = count($memberIds);
        $totalInserted = 0;

        foreach ($targets as $state => $target) {
            // Dấu seed cố định, không kèm thời gian, để lần chạy sau đếm lại được
            $seedMark = 'seed-group-inform-' . $groupId . '-' . $state;

            $sql = 'SELECT COUNT(*) FROM ' . $informTable
                . " WHERE sender_role = 'group' AND sender_group = :group_id"
                . ' AND message LIKE :seed AND ' . $stateWhere[$state];
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':group_id', $groupId, \PDO::PARAM_INT);
            $stmt->bindValue(':seed', '%' . $seedMark . '%', \PDO::PARAM_STR);
            $stmt->bindValue(':now1', $now, \PDO::PARAM_INT);
            if ($state === 'active') {
                $stmt->bindValue(':now2', $now, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $current = (int) $stmt->fetchColumn();

            $values = [];
            for ($index = $current + 1; $index <= $target; $index++) {
                switch ($state) {
                    case 'waiting':
                        $addTime = $now + rand(2, 30) * 86400;
                        $expTime = $addTime + rand(30, 90) * 86400;
                        break;
                    case 'expired':
                        $addTime = $now - rand(30, 120) * 86400;
                        // Chỉ vừa hết hạn, chưa tới lượt cronjob inform_exp_del dọn đi
                        $expTime = $now - rand(1, 5) * 86400;
                        break;
                    default:
                        $addTime = $now - rand(1, 90) * 86400;
                        // Một nửa không hạn, nửa còn lại hạn rất xa, để lần chạy sau vẫn còn active
                        $expTime = ($index % 2 === 0) ? 0 : $now + rand(60, 180) * 86400;
                        break;
                }

                // Cứ 3 dòng thì 1 dòng gửi cả nhóm, còn lại gửi đích danh vài thành viên
                $receiverIds = '';
                if ($index % 3 !== 0) {
                    $picked = [
                        (int) $memberIds[($index - 1) % $memberCount],
                        (int) $memberIds[$index % $memberCount],
                    ];
                    if ($index % 5 === 0) {
                        $picked[] = (int) $memberIds[($index + 1) % $memberCount];
                    }
                    $receiverIds = implode(',', array_values(array_unique($picked)));
                }

                $messageJson = $esc(json_encode([
                    'isdef' => 'vi',
                    'contents' => [
                        'vi' => '[' . $seedMark . '] Thông báo mẫu #' . $index . ' của nhóm gửi tới thành viên',
                        'en' => '[' . $seedMark . '] Sample group notification #' . $index,
                    ],
                ], JSON_UNESCAPED_UNICODE));

                // Cứ 6 dòng thì 1 dòng không kèm liên kết
                $linkJson = '';
                if ($index % 6 !== 0) {
                    $linkJson = $esc(json_encode([
                        'isdef' => 'vi',
                        'contents' => [
                            'vi' => 'index.php?' . $seedMark . '&item=' . $index,
                            'en' => 'https://example.com/' . $seedMark . '/' . $index,
                        ],
                    ], JSON_UNESCAPED_UNICODE));
                }

                $values[] = sprintf(
                    "('','%s','group',%d,0,'%s','%s',%d,%d)",
                    $esc($receiverIds),
                    $groupId,
                    $messageJson,
                    $linkJson,
                    $addTime,
                    $expTime
                );
            }

            if (!empty($values)) {
                $totalInserted += (int) $db->exec(
                    'INSERT INTO ' . $informTable
                    . ' (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time)'
                    . ' VALUES ' . implode(',', $values)
                );
            }
        }

        // Trạng thái đọc để cột lượt xem trong danh sách không trống. Khóa UNIQUE
        // (pid, userid) cộng INSERT IGNORE nên chạy lại không nhân bản.
        $stmt = $db->prepare(
            'SELECT id FROM ' . $informTable
            . " WHERE sender_role = 'group' AND sender_group = :group_id AND message LIKE :seed"
            . ' ORDER BY id ASC LIMIT 30'
        );
        $stmt->bindValue(':group_id', $groupId, \PDO::PARAM_INT);
        $stmt->bindValue(':seed', '%seed-group-inform-' . $groupId . '-active%', \PDO::PARAM_STR);
        $stmt->execute();
        $activeIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $statusValues = [];
        $statusUsers = array_slice($memberIds, 0, min(6, $memberCount));
        foreach ($activeIds as $order => $pid) {
            foreach ($statusUsers as $offset => $userId) {
                // Người thứ ba chỉ được hiện chứ chưa xem, để lượt xem không bằng nhau
                $shownTime = $now - rand(600, 20 * 86400);
                $viewedTime = ($offset === 2) ? 0 : $shownTime + rand(60, 7200);

                $statusValues[] = sprintf(
                    '(%d,%d,%d,%d,%d,%d)',
                    (int) $pid,
                    (int) $userId,
                    $shownTime,
                    $viewedTime,
                    ($order % 4 === 0 && $offset === 0) ? $shownTime + rand(120, 3600) : 0,
                    ($order % 5 === 0 && $offset === 1) ? $shownTime + rand(180, 5400) : 0
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

        // Đếm lại đúng theo bộ lọc active của module để chắc chắn đủ số trang
        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM ' . $informTable
            . " WHERE sender_role = 'group' AND sender_group = :group_id"
            . ' AND ' . $stateWhere['active']
        );
        $stmt->bindValue(':group_id', $groupId, \PDO::PARAM_INT);
        $stmt->bindValue(':now1', $now, \PDO::PARAM_INT);
        $stmt->bindValue(':now2', $now, \PDO::PARAM_INT);
        $stmt->execute();
        $activeCount = (int) $stmt->fetchColumn();

        $this->assertGreaterThanOrEqual(
            $targets['active'],
            $activeCount,
            'Nhóm ' . $groupId . ' chưa đủ ' . $targets['active'] . ' thông báo đang hiệu lực.'
        );
        $this->assertGreaterThanOrEqual(
            3,
            (int) ceil($activeCount / $perPage),
            'Số thông báo đang hiệu lực chưa đủ để phân trang 3 trang.'
        );
        $this->assertGreaterThanOrEqual(0, $totalInserted);
    }
}
