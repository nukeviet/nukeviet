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
}
