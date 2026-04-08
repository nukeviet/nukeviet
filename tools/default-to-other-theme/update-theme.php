<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * Xử lý các block nếu giao diện của bạn có sử dụng trong CSDL.
 * Khi nâng cấp lên giao diện future thì toàn bộ block trong các module
 * sẽ thay thế bằng cách xử lý của Smarty và giao diện mới. Do đó nếu giao diện
 * có đang dùng thì chép vào themes/ten-theme/module-file/block.name.php
 *
 * block của giao diện không chịu ảnh hưởng.
 *
 * Chép các themes/default/theme_xxx.php vào nếu chưa có
 * Chép các themes/default/system/*.tpl sang nếu nó chưa có
 */

define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

if (!defined('NV_IS_GODADMIN')) {
    exit("Not allowed");
}
echo '<pre><code>';

$error = 0;

// Lấy tất cả ngôn ngữ đã cài đặt
$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1 ORDER BY weight ASC';
$array_sitelangs = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

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

// Quét giao diện hiện có
$themes = array_diff(nv_scandir(NV_ROOTDIR . '/themes', '/^(?!(admin_|mobile_))\w+/'), ['default', 'future']);
if (empty($themes)) {
    echo "<span style='color: red;'>Không có giao diện thêm nào</span>\n";
    exit(1);
}

// Lặp từng giao diện và chép toàn bộ default sang cho đầy đủ 1 giao diện độc lập hoàn toàn
foreach ($themes as $theme) {
    echo "Sync theme " . $theme . ":\n";
    copy_source_to_destination(NV_ROOTDIR . '/themes/default', NV_ROOTDIR . '/themes/' . $theme);
    echo "Success\n";
}

// Chép theme.php của các module hệ thống sang từng giao diện tùy biến
echo "Copy theme.php of system modules:\n";
foreach ($themes as $theme) {
    foreach ($system_modules as $module) {
        $source = NV_ROOTDIR . '/modules/' . $module . '/theme.php';
        $destination = NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $module . '/theme.php';
        if (file_exists($source) && !file_exists($destination)) {
            copy_source_to_destination($source, $destination);
            echo 'Copy: ' . $theme . '/modules/' . $module . "/theme.php\n";
        }
    }
}
echo "\n";

$array_moved = [];

/**
 * Lặp tất cả ngôn ngữ, chép toàn bộ block có sử dụng trên site của tất cả các ngôn ngữ sang giao diện riêng
 */
foreach ($array_sitelangs as $lang) {
    echo "Lang " . $lang . ":\n";

    $prefixlang = $db_config['prefix'] . '_' . $lang;
    $sql = "SELECT tb1.*, tb2.module_file FROM " . $prefixlang . "_blocks_groups tb1
    LEFT JOIN " . $prefixlang . "_modules tb2 ON tb1.module=tb2.title
    WHERE tb1.module!='theme' AND tb1.theme NOT IN('default', 'mobile_default')";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if (empty($row['module_file'])) {
            print_r($row);
            die("Lỗi không có module\n");
        }
        // Các module không phải của hệ thống thì nó như cũ nên không cần chuyển
        if (!in_array($row['module_file'], $system_modules)) {
            continue;
        }
        // Chuyển rồi thì không chuyển nữa
        if (isset($array_moved[$row['theme']][$row['module_file']]) and in_array($row['file_name'], $array_moved[$row['theme']][$row['module_file']])) {
            continue;
        }

        // Kiểm tra tệp block
        $source = NV_ROOTDIR . '/modules/' . $row['module_file'] . '/blocks/' . $row['file_name'];
        $destination = NV_ROOTDIR . '/themes/' . $row['theme'] . '/modules/' . $row['module_file'] . '/' . $row['file_name'];

        // Bên trên đã chép sẵn nên trường hợp không có thư mục module là lỗi
        if (!file_exists(dirname($destination))) {
            $folder = 'themes/' . $row['theme'] . '/modules/' . $row['module_file'];
            echo "<span style='color: red;'>Không tồn tại thư mục " . $folder . "</span>\n";
            $error++;
            break 2;
        }
        if (!file_exists($destination)) {
            // Chép
            copy_source_to_destination($source, $destination);
            echo 'Copy: ' . $row['module_file'] . ": " . $row['file_name'] . "\n";
        }

        $basename = preg_replace('/\.php$/i', '', $row['file_name']);
        $sourceIni = NV_ROOTDIR . '/modules/' . $row['module_file'] . '/blocks/' . $basename . '.ini';
        $sourceJson = NV_ROOTDIR . '/modules/' . $row['module_file'] . '/blocks/' . $basename . '.json';
        $destinationIni = NV_ROOTDIR . '/themes/' . $row['theme'] . '/modules/' . $row['module_file'] . '/' . $basename . '.ini';
        $destinationJson = NV_ROOTDIR . '/themes/' . $row['theme'] . '/modules/' . $row['module_file'] . '/' . $basename . '.json';
        if (file_exists($sourceIni) and !file_exists($destinationIni)) {
            copy_source_to_destination($sourceIni, $destinationIni);
            echo 'Copy: ' . $row['module_file'] . ": " . $basename . ".ini\n";
        }
        if (file_exists($sourceJson) and !file_exists($destinationJson)) {
            copy_source_to_destination($sourceJson, $destinationJson);
            echo 'Copy: ' . $row['module_file'] . ": " . $basename . ".json\n";
        }

        $array_moved[$row['theme']][$row['module_file']][] = $row['file_name'];
    }

    echo "\n";
}

$nv_Cache->delAll(true);

echo '</code></pre>';

if ($error) {
    echo "<h1 style='color: red;'>Error!</h1>";
} else {
    echo "<h1>Success!</h1>";
}

function copy_source_to_destination(string $source, string $destination): void
{
    if (!file_exists($source)) {
        echo "<span style='color: red;'>Nguồn không tồn tại: $source</span>\n";
        exit(1);
    }

    // Nếu đích không tồn tại, tạo thư mục đích (mkdir -p tương tự)
    $destinationDir = is_dir($destination) ? $destination : dirname($destination);
    if (!file_exists($destinationDir) && !mkdir($destinationDir, 0755, true)) {
        echo "<span style='color: red;'>Không thể tạo thư mục đích: $destinationDir</span>\n";
        exit(1);
    }

    if (is_dir($source)) {
        // Nguồn là thư mục, chép toàn bộ thư mục và nội dung bên trong
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            /** @disregard P1013 */
            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                // Tạo thư mục nếu là thư mục
                if (!file_exists($destPath) && !mkdir($destPath, 0755, true)) {
                    echo "<span style='color: red;'>Không thể tạo thư mục: $destPath</span>\n";
                    exit(1);
                }
            } else {
                // Chép tệp tin nếu là tệp, chỉ chép nếu đích chưa tồn tại
                if (!file_exists($destPath) && !copy($item, $destPath)) {
                    echo "<span style='color: red;'>Không thể chép tệp: $item tới $destPath</span>\n";
                    exit(1);
                }
            }
        }
    } else {
        // Nguồn là tệp tin, chép trực tiếp đến đích, chỉ chép nếu đích chưa tồn tại
        if (!file_exists($destination) && !copy($source, $destination)) {
            echo "<span style='color: red;'>Không thể chép tệp: $source tới $destination</span>\n";
            exit(1);
        }
    }
}
