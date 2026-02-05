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
     * - [$var, ...] = ...->fetch(...) (không có ?: operator)
     * - list($var, ...) = ...->fetch(...) (không có ?: operator)
     *
     * @group php85
     * @group compatibility
     */
    public function testArrayDestructuringWithPdoFetch()
    {
        $srcDir = NV_ROOTDIR . '/src';
        $problematic_files = [];
        $safe_while_loops = [];

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
                    // Kiểm tra nếu là while loop - đây là safe pattern
                    $isWhileLoop = preg_match('/^\s*while\s*\(/', $line);
                    
                    // Tìm pattern: [$var, ...] = ...->fetch(
                    if (preg_match('/\[\s*\$[^\]]+\]\s*=.*->\s*fetch\s*\(/', $line)) {
                        // Bỏ qua template fetch
                        if (preg_match('/\$tpl\s*->\s*fetch|template\s*->\s*fetch/i', $line)) {
                            continue;
                        }
                        
                        $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                        
                        if ($isWhileLoop) {
                            $safe_while_loops[] = [
                                'file' => $relativePath,
                                'line' => $lineNum + 1,
                                'code' => trim($line)
                            ];
                        } else {
                            // Kiểm tra nếu đã có ?: operator (đã được fix)
                            if (!preg_match('/\?\s*:\s*\[/', $line)) {
                                $problematic_files[] = [
                                    'file' => $relativePath,
                                    'line' => $lineNum + 1,
                                    'code' => trim($line)
                                ];
                            }
                        }
                    }

                    // Tìm pattern: list($var, ...) = ...->fetch(
                    if (preg_match('/list\s*\([^)]+\)\s*=.*->\s*fetch\s*\(/', $line)) {
                        $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                        
                        if ($isWhileLoop) {
                            $safe_while_loops[] = [
                                'file' => $relativePath,
                                'line' => $lineNum + 1,
                                'code' => trim($line)
                            ];
                        } else {
                            // Kiểm tra nếu đã có ?: operator (đã được fix)
                            if (!preg_match('/\?\s*:\s*\[/', $line)) {
                                $problematic_files[] = [
                                    'file' => $relativePath,
                                    'line' => $lineNum + 1,
                                    'code' => trim($line)
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Ghi log ra file để dễ kiểm tra
        $logDir = NV_ROOTDIR . '/tests/_output';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/php85_compatibility_issues.log';
        $logContent = "PHP 8.5 Compatibility Test Results\n";
        $logContent .= "===================================================================\n\n";
        $logContent .= "Tìm thấy " . count($safe_while_loops) . " trường hợp an toàn (while loops)\n";
        $logContent .= "Tìm thấy " . count($problematic_files) . " trường hợp CẦN SỬA (direct assignments)\n\n";

        if (!empty($problematic_files)) {
            $logContent .= "CÁC TRƯỜNG HỢP CẦN SỬA:\n";
            $logContent .= str_repeat('=', 80) . "\n\n";
            
            foreach ($problematic_files as $issue) {
                $logContent .= "File: {$issue['file']}\n";
                $logContent .= "Line: {$issue['line']}\n";
                $logContent .= "Code: {$issue['code']}\n";
                $logContent .= str_repeat('-', 80) . "\n";
            }
        } else {
            $logContent .= "✓ Tất cả các trường hợp không an toàn đã được sửa!\n";
        }

        file_put_contents($logFile, $logContent);
        codecept_debug("Đã ghi log kết quả kiểm tra vào: tests/_output/php85_compatibility_issues.log");
        codecept_debug("Số while loops an toàn: " . count($safe_while_loops));
        codecept_debug("Số trường hợp cần sửa: " . count($problematic_files));

        // Test sẽ fail nếu phát hiện các pattern chưa được fix
        $this->assertEmpty(
            $problematic_files,
            "Phát hiện " . count($problematic_files) . " trường hợp sử dụng array destructuring với PDO fetch không an toàn. " .
            "PDO fetch() có thể trả về false khi không có kết quả, gây lỗi TypeError trong PHP 8.5. " .
            "Xem chi tiết trong tests/_output/php85_compatibility_issues.log"
        );
    }

    /**
     * Test kiểm tra PDO fetch có thể trả về false
     * Test này mô phỏng hành vi của PHP 8.5+ để đảm bảo code của chúng ta tương thích
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

        // Kiểm tra rằng pattern mới của chúng ta xử lý đúng false
        // Pattern cũ: [$test] = $result->fetch(3) sẽ gây TypeError trong PHP 8.5+
        // Pattern mới: [$test] = $result->fetch(3) ?: [null] hoạt động trong mọi version
        
        // Test pattern mới hoạt động đúng
        $result = $db->query($sql);
        [$test] = $result->fetch(3) ?: [null];
        $this->assertNull($test, "Pattern mới phải xử lý được fetch() trả về false");
        
        // Test với dữ liệu thật
        $sql = "SELECT 'test_value' as value";
        $result = $db->query($sql);
        [$value] = $result->fetch(3) ?: [null];
        $this->assertEquals('test_value', $value, "Pattern mới phải giữ được giá trị khi có dữ liệu");
    }
}
