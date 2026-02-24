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

class HttpExceptionTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Kiểm tra HttpException không có http_response_code() thừa ở dòng trên
     * HttpException tự động xử lý HTTP status code nên không cần http_response_code() trước đó
     *
     * @link https://github.com/nukeviet/nukeviet/issues/3855
     *
     * @group install
     * @group all
     */
    public function testHttpExceptionWithoutRedundantHttpResponseCode()
    {
        $files = $this->tester->listFile(NV_ROOTDIR);
        foreach ($files as $file) {
            // Bỏ qua các file trong includes/vendor ngoại trừ includes/vendor/vinades
            if (str_starts_with($file, 'includes/vendor/')) {
                if (!str_starts_with($file, 'includes/vendor/vinades/')) {
                    continue; // Bỏ qua các thư viện bên ngoài ngoại trừ vinades
                }
            }

            $lines = file(NV_ROOTDIR . '/' . $file);
            foreach ($lines as $i => $line) {
                // Kiểm tra HttpException
                if (preg_match('/\bthrow\s+new\s+.*HttpException\s*\(/', $line)) {
                    // Kiểm tra dòng liền kề phía trên có http_response_code không
                    if ($i > 0 && preg_match('/\bhttp_response_code\s*\(/', $lines[$i - 1])) {
                        $this->assertTrue(
                            false,
                            "Redundant http_response_code() found before HttpException at {$file} on line " . ($i + 1) . ". HttpException handles HTTP status code automatically, remove the http_response_code() call."
                        );
                    }
                }
            }
        }
    }
}
