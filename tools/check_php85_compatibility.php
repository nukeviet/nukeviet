<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * Tool để kiểm tra tương thích PHP 8.5
 * Phát hiện các trường hợp sử dụng array destructuring với PDO fetch
 * 
 * Cách sử dụng:
 * php tools/check_php85_compatibility.php
 */

define('NV_ROOTDIR', dirname(__DIR__));

echo "=============================================================\n";
echo "PHP 8.5 Compatibility Checker\n";
echo "Kiểm tra array destructuring với PDO fetch\n";
echo "=============================================================\n\n";

$srcDir = NV_ROOTDIR . '/src';
$problematic_files = [];

// Tìm tất cả các file PHP
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

echo "Đang quét thư mục: {$srcDir}\n\n";

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filepath = $file->getPathname();
        $content = file_get_contents($filepath);
        $lines = explode("\n", $content);

        foreach ($lines as $lineNum => $line) {
            // Tìm pattern: [$var, ...] = ...->fetch(
            if (preg_match('/\[\s*\$[^\]]+\]\s*=.*->\s*fetch\s*\(/', $line)) {
                $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                $problematic_files[] = [
                    'file' => $relativePath,
                    'line' => $lineNum + 1,
                    'code' => trim($line),
                    'type' => 'array_destructuring'
                ];
            }

            // Tìm pattern: list($var, ...) = ...->fetch(
            if (preg_match('/list\s*\([^)]+\)\s*=.*->\s*fetch\s*\(/', $line)) {
                $relativePath = str_replace(NV_ROOTDIR . '/', '', $filepath);
                $problematic_files[] = [
                    'file' => $relativePath,
                    'line' => $lineNum + 1,
                    'code' => trim($line),
                    'type' => 'list_destructuring'
                ];
            }
        }
    }
}

echo "Kết quả quét:\n";
echo "=============================================================\n\n";

if (empty($problematic_files)) {
    echo "✓ Không tìm thấy vấn đề nào!\n";
    echo "Tất cả code đã tương thích với PHP 8.5\n";
    exit(0);
} else {
    echo "✗ Tìm thấy " . count($problematic_files) . " trường hợp có thể gây lỗi trong PHP 8.5\n\n";
    
    // Nhóm theo file
    $groupedByFile = [];
    foreach ($problematic_files as $issue) {
        $groupedByFile[$issue['file']][] = $issue;
    }
    
    foreach ($groupedByFile as $file => $issues) {
        echo "File: {$file}\n";
        echo str_repeat('-', 80) . "\n";
        foreach ($issues as $issue) {
            echo "  Line {$issue['line']}: {$issue['code']}\n";
        }
        echo "\n";
    }
    
    echo "\nVẤN ĐỀ:\n";
    echo "PDO fetch() trả về FALSE khi không có kết quả.\n";
    echo "Trong PHP 8.5+, việc sử dụng array destructuring với giá trị không phải array\n";
    echo "sẽ gây ra TypeError: \"Cannot use bool as array\"\n\n";
    
    echo "GIẢI PHÁP:\n";
    echo "1. Kiểm tra kết quả trước khi destructuring:\n";
    echo "   \$row = \$db->query(...)->fetch(3);\n";
    echo "   if (\$row !== false) {\n";
    echo "       [\$var1, \$var2] = \$row;\n";
    echo "   }\n\n";
    
    echo "2. Hoặc sử dụng toán tử null coalescing với giá trị mặc định:\n";
    echo "   [\$var1, \$var2] = \$db->query(...)->fetch(3) ?: [null, null];\n\n";
    
    // Ghi ra file log
    $logFile = NV_ROOTDIR . '/tests/_output/php85_compatibility_issues.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logContent = "PHP 8.5 Compatibility Issues - Array Destructuring with PDO fetch\n";
    $logContent .= "===================================================================\n";
    $logContent .= "Scan date: " . date('Y-m-d H:i:s') . "\n";
    $logContent .= "Tìm thấy " . count($problematic_files) . " trường hợp có thể gây lỗi trong PHP 8.5\n\n";

    foreach ($problematic_files as $issue) {
        $logContent .= "File: {$issue['file']}\n";
        $logContent .= "Line: {$issue['line']}\n";
        $logContent .= "Type: {$issue['type']}\n";
        $logContent .= "Code: {$issue['code']}\n";
        $logContent .= str_repeat('-', 80) . "\n";
    }

    file_put_contents($logFile, $logContent);
    echo "Chi tiết đã được ghi vào: tests/_output/php85_compatibility_issues.log\n";
    
    exit(1);
}
