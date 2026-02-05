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
 * Test để kiểm tra tương thích PHP 8.5
 * Phát hiện các pattern array destructuring với PDO fetch
 */
class Php85CompatibilityTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Quét tìm các trường hợp sử dụng array destructuring với PDO fetch
     * Các pattern cần tìm:
     * - [$var, ...] = ...->fetch(...)
     * - list($var, ...) = ...->fetch(...)
     *
     * @group php85
     * @group compatibility
     */
    public function testArrayDestructuringWithPdoFetch()
    {
        $srcDir = NV_ROOTDIR . '/src';
        $problematic_files = [];

        // Tìm tất cả các file PHP
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filepath = $file->getPathname();
                $content = file_get_contents($filepath);
                $lines = explode("\n", $content);

                foreach ($lines as $lineNum => $line) {
                    // Tìm pattern: [$var, ...] = ...->fetch(
                    if (preg_match('/\[\s*\$[^\]]+\]\s*=.*->\s*fetch\s*\(/', $line)) {
                        // Kiểm tra xem có xử lý giá trị trả về hay không
                        // Nếu không có kiểm tra, đây là lỗi tiềm ẩn
                        $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                        $problematic_files[] = [
                            'file' => $relativePath,
                            'line' => $lineNum + 1,
                            'code' => trim($line)
                        ];
                    }

                    // Tìm pattern: list($var, ...) = ...->fetch(
                    if (preg_match('/list\s*\([^)]+\)\s*=.*->\s*fetch\s*\(/', $line)) {
                        $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                        $problematic_files[] = [
                            'file' => $relativePath,
                            'line' => $lineNum + 1,
                            'code' => trim($line)
                        ];
                    }
                }
            }
        }

        // Ghi log ra file để dễ kiểm tra
        if (!empty($problematic_files)) {
            $logFile = NV_ROOTDIR . '/tests/_output/php85_compatibility_issues.log';
            $logContent = "PHP 8.5 Compatibility Issues - Array Destructuring with PDO fetch\n";
            $logContent .= "===================================================================\n\n";
            $logContent .= "Tìm thấy " . count($problematic_files) . " trường hợp có thể gây lỗi trong PHP 8.5\n\n";

            foreach ($problematic_files as $issue) {
                $logContent .= "File: {$issue['file']}\n";
                $logContent .= "Line: {$issue['line']}\n";
                $logContent .= "Code: {$issue['code']}\n";
                $logContent .= str_repeat('-', 80) . "\n";
            }

            file_put_contents($logFile, $logContent);
            codecept_debug("Đã ghi log các vấn đề vào: tests/_output/php85_compatibility_issues.log");
        }

        // Test sẽ fail nếu phát hiện các pattern này
        // Điều này đảm bảo không có code mới nào vi phạm quy tắc
        $this->assertEmpty(
            $problematic_files,
            "Phát hiện " . count($problematic_files) . " trường hợp sử dụng array destructuring với PDO fetch không an toàn. " .
            "PDO fetch() có thể trả về false khi không có kết quả, gây lỗi TypeError trong PHP 8.5. " .
            "Xem chi tiết trong tests/_output/php85_compatibility_issues.log"
        );
    }

    /**
     * Test kiểm tra PDO fetch có thể trả về false
     *
     * @group php85
     * @group pdo
     */
    public function testPdoFetchReturnsFalse()
    {
        global $db, $db_config;

        // Tạo query trả về kết quả rỗng
        $sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_extensions WHERE 1=0";
        $result = $db->query($sql);

        // Kiểm tra fetch trả về false khi không có kết quả
        $row = $result->fetch(3);
        $this->assertFalse($row, "PDO fetch() phải trả về false khi không có kết quả");

        // Kiểm tra rằng không thể sử dụng array destructuring với false
        // Trong PHP 8.5, điều này sẽ gây TypeError
        try {
            // Đoạn code này sẽ fail trong PHP 8.5+
            if (PHP_VERSION_ID >= 80500) {
                [$test] = $result->fetch(3);
                $this->fail("Không nên đến được đây - array destructuring với false phải throw TypeError");
            }
        } catch (\TypeError $e) {
            // Đây là hành vi mong đợi trong PHP 8.5+
            $this->assertTrue(true);
        }
    }
}
