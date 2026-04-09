<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * Sync toàn bộ giao diện themes/default sang themes/mobile_default.
 *
 * Bước 1: Chép đệ quy toàn bộ nội dung themes/default → themes/mobile_default
 *         (chỉ chép các tệp chưa tồn tại ở đích, không ghi đè).
 *
 * Bước 2: Quét toàn bộ module trong modules/ (không dùng CSDL), với mỗi module:
 *   - Chép theme.php vào themes/mobile_default/modules/{module}/theme.php
 *   - Chép toàn bộ tệp trong modules/{module}/blocks/ vào
 *     themes/mobile_default/modules/{module}/
 *
 * Nguyên tắc: chỉ chép nếu tệp đích chưa tồn tại, không chép đè.
 */

define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

if (!defined('NV_IS_GODADMIN')) {
    exit("Not allowed");
}

echo '<pre><code>';

$copied = 0;
$skipped = 0;
$errors = 0;

$defaultDir = NV_ROOTDIR . '/themes/default';
$mobileDir  = NV_ROOTDIR . '/themes/mobile_default';

if (!is_dir($defaultDir)) {
    echo "<span style='color: red;'>Không tìm thấy thư mục nguồn: themes/default</span>\n";
    exit(1);
}

// =====================================================================
// Bước 1: Chép toàn bộ cây thư mục themes/default → themes/mobile_default
// =====================================================================
echo "=== Bước 1: Sync themes/default → themes/mobile_default ===\n";

if (!is_dir($mobileDir)) {
    if (!mkdir($mobileDir, 0755, true)) {
        echo "<span style='color: red;'>Không thể tạo thư mục: themes/mobile_default</span>\n";
        exit(1);
    }
    echo "Tạo thư mục: themes/mobile_default\n";
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($defaultDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    /** @disregard P1013 */
    $subPath = $iterator->getSubPathName();
    $destPath = $mobileDir . DIRECTORY_SEPARATOR . $subPath;

    if ($item->isDir()) {
        if (!is_dir($destPath) && !mkdir($destPath, 0755, true)) {
            echo "<span style='color: red;'>  Không thể tạo thư mục: themes/mobile_default/$subPath</span>\n";
            $errors++;
        }
    } else {
        if (!file_exists($destPath)) {
            if (copy((string) $item, $destPath)) {
                echo "  Copy: themes/mobile_default/$subPath\n";
                $copied++;
            } else {
                echo "<span style='color: red;'>  Lỗi chép: themes/mobile_default/$subPath</span>\n";
                $errors++;
            }
        } else {
            $skipped++;
        }
    }
}

echo "\n";

// =====================================================================
// Bước 2: Chép theme.php + toàn bộ blocks/ từ tất cả module trong modules/
// =====================================================================
echo "=== Bước 2: Sync module theme.php và blocks/ → themes/mobile_default/modules/ ===\n";

$modulesBaseDir = NV_ROOTDIR . '/modules';

if (!is_dir($modulesBaseDir)) {
    echo "<span style='color: red;'>Không tìm thấy thư mục: modules/</span>\n";
    exit(1);
}

// Quét danh sách module từ hệ thống tệp (bỏ qua index.html và entry không phải thư mục)
$moduleEntries = scandir($modulesBaseDir);
foreach ($moduleEntries as $module) {
    if ($module === '.' || $module === '..' || $module === 'index.html') {
        continue;
    }

    $moduleDir = $modulesBaseDir . '/' . $module;
    if (!is_dir($moduleDir)) {
        continue;
    }

    $destModuleDir = $mobileDir . '/modules/' . $module;

    // Tạo thư mục module đích nếu chưa có
    if (!is_dir($destModuleDir)) {
        if (!mkdir($destModuleDir, 0755, true)) {
            echo "<span style='color: red;'>  Lỗi: Không thể tạo thư mục themes/mobile_default/modules/$module</span>\n";
            $errors++;
            continue;
        }
        echo "  Tạo thư mục: themes/mobile_default/modules/$module\n";
    }

    // -- Chép theme.php --
    $sourceTheme = $moduleDir . '/theme.php';
    $destTheme   = $destModuleDir . '/theme.php';

    if (file_exists($sourceTheme)) {
        if (!file_exists($destTheme)) {
            if (copy($sourceTheme, $destTheme)) {
                echo "  Copy: themes/mobile_default/modules/$module/theme.php\n";
                $copied++;
            } else {
                echo "<span style='color: red;'>  Lỗi chép: themes/mobile_default/modules/$module/theme.php</span>\n";
                $errors++;
            }
        } else {
            $skipped++;
        }
    }

    // -- Chép toàn bộ tệp trong blocks/ --
    $blocksDir = $moduleDir . '/blocks';
    if (!is_dir($blocksDir)) {
        continue;
    }

    $blockEntries = scandir($blocksDir);
    foreach ($blockEntries as $file) {
        if ($file === '.' || $file === '..' || $file === 'index.html') {
            continue;
        }

        $sourceFile = $blocksDir . '/' . $file;
        if (!is_file($sourceFile)) {
            continue;
        }

        $destFile = $destModuleDir . '/' . $file;
        if (!file_exists($destFile)) {
            if (copy($sourceFile, $destFile)) {
                echo "  Copy: themes/mobile_default/modules/$module/$file\n";
                $copied++;
            } else {
                echo "<span style='color: red;'>  Lỗi chép: themes/mobile_default/modules/$module/$file</span>\n";
                $errors++;
            }
        } else {
            $skipped++;
        }
    }
}

echo '</code></pre>';

echo "<h3>Tổng kết: đã chép <strong>$copied</strong> tệp, bỏ qua <strong>$skipped</strong> tệp đã tồn tại.</h3>";

if ($errors) {
    echo "<h1 style='color: red;'>Có $errors lỗi!</h1>";
} else {
    echo "<h1>Done!</h1>";
}
