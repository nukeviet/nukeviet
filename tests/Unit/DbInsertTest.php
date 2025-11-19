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

/**
 * Các bài test chèn vào CSDL
 */
class DbInsertTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Tạo một địa chỉ IPv4 công cộng ngẫu nhiên
     */
    protected function randomPublicIPv4()
    {
        while (true) {
            $ip = rand(1, 255) . '.' .
                rand(0, 255) . '.' .
                rand(0, 255) . '.' .
                rand(0, 255);

            // Tách octet
            list($a, $b) = explode('.', $ip);

            // Loại private / loopback
            if ($a == 10) continue;
            if ($a == 127) continue;
            if ($a == 192 && $b == 168) continue;
            if ($a == 172 && ($b >= 16 && $b <= 31)) continue;

            return $ip;
        }
    }

    /**
     * Tạo một timestamp ngẫu nhiên tăng dần theo chỉ số i
     * @param mixed $i
     * @return int
     */
    protected function getRandomIncreasingTime($i)
    {
        // Giới hạn i hợp lệ
        if ($i < 0) $i = 0;
        if ($i > 19) $i = 19;

        $end = NV_CURRENTTIME; // mốc mới nhất
        $start = $end - 3600 * 24 * 30; // lùi tối đa 30 ngày

        // Chia làm đúng 20 đoạn thời gian
        $step = intval(($end - $start) / 20);

        // Tính min/max của đoạn thứ i
        $min = $start + $step * $i;
        $max = $start + $step * ($i + 1);

        // Trả về timestamp ngẫu nhiên trong đoạn
        return mt_rand($min, $max);
    }

    /**
     * @group db
     * @group db-users
     */
    public function testCreateLoginSession()
    {
        global $db, $db_config;

        $array_uas = [
            // Windows
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko", // IE11
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/125.0.0.0 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Opera/104.0.0.0 Safari/537.36",

            // macOS / Apple
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_5) Gecko/20100101 Firefox/125.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1",
            "Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1",

            // Android - Chrome / Firefox / Opera Mini
            "Mozilla/5.0 (Linux; Android 14; SM-S921B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36",
            "Mozilla/5.0 (Android 14; Mobile; rv:125.0) Gecko/125.0 Firefox/125.0",
            "Mozilla/5.0 (Linux; Android 14; SM-S921B) AppleWebKit/537.36 (KHTML, like Gecko) Opera/104.0.0.0 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; U; Android 14; en-us; GT-I9500 Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 OPR/78.0.0.0", // Opera Mini
            "Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36",

            // Linux
            "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:125.0) Gecko/20100101 Firefox/125.0",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Opera/104.0.0.0 Safari/537.36",

            // Legacy Mozilla (để match key 'mozilla')
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Mozilla/40.0",

            // Extra Safari iPod
            "Mozilla/5.0 (iPod touch; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15A372 Safari/604.1"
        ];

        for ($i = 0; $i < 20; $i++) {
            $clid = md5(nv_genpass(32));
            $logtime = $this->getRandomIncreasingTime($i);
            $ip = $this->randomPublicIPv4();
            $agent = $array_uas[$i];

            $sql = "INSERT INTO " . $db_config['prefix'] . "_users_login (
                userid, clid, ip, logtime, mode, agent, mode_extra
            ) VALUES (
                1, :clid, :ip, :logtime, 0, :agent, ''
            )";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':clid', $clid, \PDO::PARAM_STR);
            $stmt->bindParam(':ip', $ip, \PDO::PARAM_STR);
            $stmt->bindParam(':logtime', $logtime, \PDO::PARAM_INT);
            $stmt->bindParam(':agent', $agent, \PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}
