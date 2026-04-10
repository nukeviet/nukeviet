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
 * Chép theme.php của từng module hệ thống và toàn bộ các tệp block
 * trong modules/{module}/blocks/ vào themes/default và themes/mobile_default.
 *
 * Nguyên tắc: chỉ chép nếu tệp đích chưa tồn tại, không chép đè.
 *
 * Đích đến:
 * - themes/default/modules/ten-module/
 * - themes/mobile_default/modules/ten-module/
 */

define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

if (!defined('NV_IS_GODADMIN')) {
    exit("Not allowed");
}

echo '<pre><code>';

$system_modules = [
    "banners",
    "comment",
    "contact",
    "feeds",
    "freecontent",
    "inform",
    "menu",
    "myapi",
    "news",
    "page",
    "seek",
    "statistics",
    "two-step-verification",
    "users",
    "voting",
    "zalo"
];

$target_themes = ['default', 'mobile_default'];

$copied = 0;
$skipped = 0;
$errors = 0;

foreach ($target_themes as $theme) {
    echo "=== Theme: $theme ===\n";

    $themeModulesDir = NV_ROOTDIR . '/themes/' . $theme . '/modules';

    foreach ($system_modules as $module) {
        $moduleDir = NV_ROOTDIR . '/modules/' . $module;

        if (!is_dir($moduleDir)) {
            echo "  [SKIP] Module không tồn tại: $module\n";
            continue;
        }

        $destModuleDir = $themeModulesDir . '/' . $module;

        // Tạo thư mục đích nếu chưa có
        if (!is_dir($destModuleDir)) {
            if (!mkdir($destModuleDir, 0755, true)) {
                echo "<span style='color: red;'>  Lỗi: Không thể tạo thư mục themes/$theme/modules/$module</span>\n";
                $errors++;
                continue;
            }
            echo "  Tạo thư mục: themes/$theme/modules/$module\n";
        }

        // Chép theme.php của module
        $sourceTheme = $moduleDir . '/theme.php';
        $destTheme = $destModuleDir . '/theme.php';

        if (file_exists($sourceTheme)) {
            if (!file_exists($destTheme)) {
                if (copy($sourceTheme, $destTheme)) {
                    echo "  Copy: themes/$theme/modules/$module/theme.php\n";
                    $copied++;
                } else {
                    echo "<span style='color: red;'>  Lỗi chép: themes/$theme/modules/$module/theme.php</span>\n";
                    $errors++;
                }
            } else {
                $skipped++;
            }
        }

        // Chép toàn bộ tệp trong blocks/
        $blocksDir = $moduleDir . '/blocks';

        if (!is_dir($blocksDir)) {
            continue;
        }

        $blockFiles = scandir($blocksDir);
        foreach ($blockFiles as $file) {
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
                    echo "  Copy: themes/$theme/modules/$module/$file\n";
                    $copied++;
                } else {
                    echo "<span style='color: red;'>  Lỗi chép: themes/$theme/modules/$module/$file</span>\n";
                    $errors++;
                }
            } else {
                $skipped++;
            }
        }
    }

    echo "\n";
}

echo '</code></pre>';

echo "<h3>Tổng kết: đã chép <strong>$copied</strong> tệp, bỏ qua <strong>$skipped</strong> tệp đã tồn tại.</h3>";

if ($errors) {
    echo "<h1 style='color: red;'>Có $errors lỗi!</h1>";
} else {
    echo "<h1>Done!</h1>";
}
