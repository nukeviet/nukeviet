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
     * Kiểm tra các exception được sử dụng đúng cách
     * - HttpException: Thay thế trigger_error(256) và tự động xử lý HTTP status code
     * - RuntimeException/InvalidArgumentException: Các lỗi hệ thống/validation không cần HTTP code tùy chỉnh
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
                // Kiểm tra trigger_error cũ và RuntimeException/InvalidArgumentException (không nên dùng với HTTP code)
                if (preg_match('/\btrigger_error\s*\(.*,\s*(256|E_USER_ERROR)\s*\)/', $line)) {
                    // trigger_error(256) đã deprecated, không nên tồn tại
                    $this->assertTrue(
                        false,
                        "Deprecated trigger_error(256) found at {$file} on line " . ($i + 1) . ". Use HttpException instead."
                    );
                }
                
                // RuntimeException/InvalidArgumentException không nên có http_response_code trước đó
                // (vì giờ nên dùng HttpException)
                if (preg_match('/\bthrow\s+new\s+(RuntimeException|InvalidArgumentException)\s*\(/', $line)) {
                    $foundHttpCode = false;
                    for ($j = $i - 1; $j >= max(0, $i - 3); $j--) {
                        if (preg_match('/\bhttp_response_code\s*\(/', $lines[$j])) {
                            $foundHttpCode = true;
                            break;
                        }
                    }
                    
                    if ($foundHttpCode) {
                        $this->assertTrue(
                            false,
                            "RuntimeException/InvalidArgumentException at {$file} on line " . ($i + 1) . " has http_response_code before it. Use HttpException instead."
                        );
                    }
                }
            }
        }
    }
}
