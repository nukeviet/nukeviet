<?php
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
