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

class StatisticsTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Ngôn ngữ thống kê
     *
     * @return array
     */
    private function getLangStat()
    {
        global $db;

        $_columns = array_keys($db->columns_array(NV_COUNTER_GLOBALTABLE));
        $columns = [];
        foreach ($_columns as $col) {
            if (preg_match('/^([a-z]{2})\_count$/i', $col, $m)) {
                $columns[] = $m[1];
            }
        }

        $this->assertNotEmpty($columns, 'No stat lang');
        return $columns;
    }

    /**
     * Thống kê giờ trong ngày
     *
     * @group install
     * @group all
     */
    public function testStatHours()
    {
        global $db;

        $columns = $this->getLangStat();

        for ($hour = 0; $hour < 24; $hour++) {
            $svalue = random_int(0, 2000);
            $sql_key = $sql_value = [];
            foreach ($columns as $lang) {
                $sql_key[] = $lang . '_count';
                $sql_value[] = $svalue;
            }
            $hour = str_pad($hour, 2, '0', STR_PAD_LEFT);

            $sql = "INSERT INTO " . NV_COUNTER_GLOBALTABLE . " (
                c_type, c_val, last_update, c_count, " . implode(', ', $sql_key) . "
            ) VALUES (
                'hour', '" . $hour . "', " . NV_CURRENTTIME . ", " . $svalue . ", " . implode(', ', $sql_value) . "
            ) ON DUPLICATE KEY UPDATE c_count=" . $svalue;

            foreach ($columns as $lang) {
                $sql .= ", " . $lang . "_count=" . $svalue;
            }

            $this->assertNotFalse($db->query($sql), $sql);
        }
    }
}
