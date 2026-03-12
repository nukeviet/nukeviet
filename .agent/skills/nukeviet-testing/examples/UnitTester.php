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

class MyComponentTest extends \Codeception\Test\Unit
{
    protected \Tests\Support\UnitTester $tester;

    protected function _before() {
        // Khởi tạo trước mỗi test
    }

    public function testFunctionality() {
        global $db;
        $result = $db->query("SELECT ...");
        $this->assertNotEmpty($result);
    }
}
