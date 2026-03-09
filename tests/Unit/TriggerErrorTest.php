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
     * Kiểm tra không còn sử dụng trigger_error với E_USER_ERROR hoặc 256
     * trigger_error(..., E_USER_ERROR) và trigger_error(..., 256) đã deprecated trong PHP 8.4
     * Cần thay thế bằng HttpException
     *
     * @link https://github.com/nukeviet/nukeviet/issues/3855
     *
     * @group install
     * @group all
     */
    public function testNoDeprecatedTriggerError()
    {
        $files = $this->tester->listFile(NV_ROOTDIR);
        foreach ($files as $file) {
            if (str_starts_with($file, 'includes/vendor/guzzlehttp')) {
                continue; // Bỏ qua các file của thư viện Guzzle
            }

            $lines = file(NV_ROOTDIR . '/' . $file);
            foreach ($lines as $i => $line) {
                // Kiểm tra trigger_error với 256 hoặc E_USER_ERROR (đã deprecated)
                if (preg_match('/\btrigger_error\s*\(.*,\s*(256|E_USER_ERROR)\s*\)/', $line)) {
                    $this->assertTrue(
                        false,
                        "Deprecated trigger_error with E_USER_ERROR or 256 found at {$file} on line " . ($i + 1) . ". Use HttpException instead."
                    );
                }
            }
        }
    }
}
