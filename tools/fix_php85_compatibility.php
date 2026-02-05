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
 * Tool để tự động sửa các vấn đề tương thích PHP 8.5
 * 
 * Cách sử dụng:
 * php tools/fix_php85_compatibility.php [--dry-run]
 * 
 * --dry-run: Chỉ hiển thị những gì sẽ được sửa mà không thực sự sửa file
 */

define('NV_ROOTDIR', dirname(__DIR__));

$dryRun = in_array('--dry-run', $argv);

echo "=============================================================\n";
echo "PHP 8.5 Compatibility Fixer\n";
echo "Sửa array destructuring với PDO fetch\n";
if ($dryRun) {
    echo "*** DRY RUN MODE - Không thực sự thay đổi file ***\n";
}
echo "=============================================================\n\n";

$srcDir = NV_ROOTDIR . '/src';
$fixed_files = [];
$skipped_files = [];

// Tìm tất cả các file PHP
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

echo "Đang quét và sửa các file trong: {$srcDir}\n\n";

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filepath = $file->getPathname();
        $content = file_get_contents($filepath);
        $originalContent = $content;
        $lines = explode("\n", $content);
        $modified = false;
        
        foreach ($lines as $lineNum => &$line) {
            // Skip các dòng trong while loop - chúng đã an toàn
            if (preg_match('/^\s*while\s*\(/', $line)) {
                continue;
            }
            
            // Pattern 1: [$var, ...] = ...->fetch(...)
            // Chuyển thành: [$var, ...] = ...->fetch(...) ?: [default values]
            if (preg_match('/^(\s*)\[([^\]]+)\]\s*=\s*(.+->fetch\([^)]*\));?\s*$/', $line, $matches)) {
                $indent = $matches[1];
                $vars = $matches[2];
                $fetchCall = $matches[3];
                
                // Đếm số biến để tạo giá trị mặc định
                $varCount = count(array_filter(explode(',', $vars)));
                $defaults = implode(', ', array_fill(0, $varCount, 'null'));
                
                // Tạo dòng mới với null coalescing operator
                $newLine = $indent . '[' . $vars . '] = ' . $fetchCall . ' ?: [' . $defaults . '];';
                
                if ($newLine !== $line) {
                    echo "File: " . str_replace(NV_ROOTDIR . '/', '', $filepath) . "\n";
                    echo "  Line " . ($lineNum + 1) . ":\n";
                    echo "  - " . trim($line) . "\n";
                    echo "  + " . trim($newLine) . "\n\n";
                    
                    $line = $newLine;
                    $modified = true;
                }
            }
            
            // Pattern 2: list($var, ...) = ...->fetch(...)
            // Chuyển thành: list($var, ...) = ...->fetch(...) ?: [default values]
            if (preg_match('/^(\s*)list\s*\(([^)]+)\)\s*=\s*(.+->fetch\([^)]*\));?\s*$/', $line, $matches)) {
                $indent = $matches[1];
                $vars = $matches[2];
                $fetchCall = $matches[3];
                
                // Đếm số biến để tạo giá trị mặc định
                $varCount = count(array_filter(explode(',', $vars)));
                $defaults = implode(', ', array_fill(0, $varCount, 'null'));
                
                // Tạo dòng mới với null coalescing operator
                $newLine = $indent . 'list(' . $vars . ') = ' . $fetchCall . ' ?: [' . $defaults . '];';
                
                if ($newLine !== $line) {
                    echo "File: " . str_replace(NV_ROOTDIR . '/', '', $filepath) . "\n";
                    echo "  Line " . ($lineNum + 1) . ":\n";
                    echo "  - " . trim($line) . "\n";
                    echo "  + " . trim($newLine) . "\n\n";
                    
                    $line = $newLine;
                    $modified = true;
                }
            }
        }
        
        if ($modified) {
            $newContent = implode("\n", $lines);
            
            if (!$dryRun) {
                file_put_contents($filepath, $newContent);
                $fixed_files[] = str_replace(NV_ROOTDIR . '/', '', $filepath);
            } else {
                $skipped_files[] = str_replace(NV_ROOTDIR . '/', '', $filepath);
            }
        }
    }
}

echo "\n=============================================================\n";
echo "KẾT QUẢ:\n";
echo "=============================================================\n";

if ($dryRun) {
    echo "Số file sẽ được sửa: " . count($skipped_files) . "\n";
    if (!empty($skipped_files)) {
        echo "\nCác file sẽ được sửa:\n";
        foreach ($skipped_files as $file) {
            echo "  - {$file}\n";
        }
    }
    echo "\nChạy lại không có --dry-run để thực sự sửa các file.\n";
} else {
    echo "Số file đã sửa: " . count($fixed_files) . "\n";
    if (!empty($fixed_files)) {
        echo "\nCác file đã sửa:\n";
        foreach ($fixed_files as $file) {
            echo "  - {$file}\n";
        }
    }
    echo "\nHoàn tất! Các file đã được cập nhật để tương thích với PHP 8.5.\n";
}

echo "\nLƯU Ý:\n";
echo "- Các dòng trong vòng lặp while không được sửa vì chúng đã an toàn.\n";
echo "- Kiểm tra kỹ các thay đổi trước khi commit.\n";
echo "- Chạy lại công cụ kiểm tra: php tools/check_php85_compatibility.php\n";
