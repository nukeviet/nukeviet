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

class TriggerErrorTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Tìm kiếm chỗ nào có trigger_error() với kiểu 256 mà không có http response code trước đó thì là lỗi
     *
     * @link https://github.com/nukeviet/nukeviet/issues/3855
     *
     * @group install
     * @group all
     */
    public function testTriggerErrorMustHaveHttpResponseCodeBefore()
    {
        $files = $this->tester->listFile(NV_ROOTDIR);
        foreach ($files as $file) {
            if (str_starts_with($file, 'includes/vendor/guzzlehttp')) {
                continue; // Bỏ qua các file của thư viện Guzzle
            }

            $lines = file(NV_ROOTDIR . '/' . $file);
            foreach ($lines as $i => $line) {
                if (preg_match('/\btrigger_error\s*\(.*,\s*(256|E_USER_ERROR)\s*\)/', $line)) {
                    $foundValidHttpCode = false;

                    // Kiểm tra tối đa 3 dòng trước (kể cả xuống dòng, space, tab)
                    for ($j = $i - 1; $j >= max(0, $i - 3); $j--) {
                        if (preg_match('/\bhttp_response_code\s*\(\s*(403|500)\s*\)\s*;/', $lines[$j])) {
                            $foundValidHttpCode = true;
                            break;
                        }
                    }

                    $this->assertFalse(
                        !$foundValidHttpCode,
                        "trigger_error at {$file} on line " . ($i + 1) . " without http_response_code(403|500) before"
                    );
                }
            }
        }
    }
}
