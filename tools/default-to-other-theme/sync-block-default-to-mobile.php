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
 * Sync toàn bộ tệp PHP block của theme default sang mobile_default.
 *
 * Quét toàn bộ tệp *.php trong themes/default/modules/* rồi chép sang
 * themes/mobile_default/modules/{ten-module}/{ten-file}.php.
 *
 * Nguyên tắc:
 *   - Nếu tệp đích đã tồn tại → chép đè (ghi đè).
 *   - Riêng theme.php → chỉ chép nếu tệp đích chưa tồn tại (không ghi đè).
 */

define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

if (!defined('NV_IS_GODADMIN')) {
    exit("Not allowed");
}

echo '<pre><code>';

$copied    = 0;
$overwrite = 0;
$skipped   = 0;
$errors    = 0;

$defaultModulesDir = NV_ROOTDIR . '/themes/default/modules';
$mobileModulesDir  = NV_ROOTDIR . '/themes/mobile_default/modules';

if (!is_dir($defaultModulesDir)) {
    echo "<span style='color: red;'>Không tìm thấy thư mục nguồn: themes/default/modules</span>\n";
    exit(1);
}

echo "=== Sync themes/default/modules/*/*.php → themes/mobile_default/modules/ ===\n\n";

// Tạo thư mục gốc đích nếu chưa có
if (!is_dir($mobileModulesDir)) {
    if (!mkdir($mobileModulesDir, 0755, true)) {
        echo "<span style='color: red;'>Không thể tạo thư mục: themes/mobile_default/modules</span>\n";
        exit(1);
    }
    echo "Tạo thư mục: themes/mobile_default/modules\n";
}

// Quét từng thư mục module trong themes/default/modules/
$moduleEntries = scandir($defaultModulesDir);
foreach ($moduleEntries as $module) {
    if ($module === '.' || $module === '..' || $module === 'index.html') {
        continue;
    }

    $srcModuleDir = $defaultModulesDir . '/' . $module;
    if (!is_dir($srcModuleDir)) {
        continue;
    }

    $destModuleDir = $mobileModulesDir . '/' . $module;

    // Tạo thư mục module đích nếu chưa có
    if (!is_dir($destModuleDir)) {
        if (!mkdir($destModuleDir, 0755, true)) {
            echo "<span style='color: red;'>  Lỗi: Không thể tạo thư mục themes/mobile_default/modules/$module</span>\n";
            $errors++;
            continue;
        }
        echo "  Tạo thư mục: themes/mobile_default/modules/$module\n";
    }

    // Quét tất cả tệp *.php trong thư mục module (không đệ quy)
    $fileEntries = scandir($srcModuleDir);
    foreach ($fileEntries as $file) {
        if ($file === '.' || $file === '..' || $file === 'index.html') {
            continue;
        }

        // Chỉ xử lý tệp .php
        if (!str_ends_with($file, '.php')) {
            continue;
        }

        $sourceFile = $srcModuleDir . '/' . $file;
        if (!is_file($sourceFile)) {
            continue;
        }

        $destFile = $destModuleDir . '/' . $file;

        // theme.php: chỉ chép nếu tệp đích chưa tồn tại
        if ($file === 'theme.php') {
            if (file_exists($destFile)) {
                $skipped++;
                continue;
            }
            if (copy($sourceFile, $destFile)) {
                echo "  Copy (mới): themes/mobile_default/modules/$module/$file\n";
                $copied++;
            } else {
                echo "<span style='color: red;'>  Lỗi chép: themes/mobile_default/modules/$module/$file</span>\n";
                $errors++;
            }
            continue;
        }

        // Các tệp còn lại: chép đè nếu đã tồn tại
        $isExisting = file_exists($destFile);
        if (copy($sourceFile, $destFile)) {
            if ($isExisting) {
                echo "  Ghi đè: themes/mobile_default/modules/$module/$file\n";
                $overwrite++;
            } else {
                echo "  Copy (mới): themes/mobile_default/modules/$module/$file\n";
                $copied++;
            }
        } else {
            echo "<span style='color: red;'>  Lỗi chép: themes/mobile_default/modules/$module/$file</span>\n";
            $errors++;
        }
    }
}

echo '</code></pre>';

echo "<h3>Tổng kết: chép mới <strong>$copied</strong> tệp, ghi đè <strong>$overwrite</strong> tệp, bỏ qua (theme.php đã tồn tại) <strong>$skipped</strong> tệp.</h3>";

if ($errors) {
    echo "<h1 style='color: red;'>Có $errors lỗi!</h1>";
} else {
    echo "<h1>Done!</h1>";
}
