<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_ADMIN', true);
define('NV_MAINFILE', true);
define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../src')));
define('NV_REPODIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/..')));

require NV_REPODIR . '/vendor/autoload.php';
require NV_ROOTDIR . '/includes/constants.php';
require NV_ROOTDIR . '/includes/core/admin_functions.php';

use Symfony\Component\Yaml\Yaml;

echo NV_ROOTDIR . "\n";
echo NV_REPODIR . "\n";

// Đọc file danh mục
$filecat = NV_REPODIR . '/node_modules/@fortawesome/fontawesome-free/metadata/categories.yml';
$yaml = Yaml::parseFile($filecat);
$categories = [];

foreach ($yaml as $key => $row) {
    foreach ($row['icons'] as $icon) {
        if (isset($categories[$icon])) {
            continue;
        }

        $categories[$icon] = [
            'id' => $key,
            'title' => $row['label']
        ];
    }
}

// Đọc list icon
$file = NV_REPODIR . '/node_modules/@fortawesome/fontawesome-free/metadata/icon-families.json';
$json = json_decode(file_get_contents($file), true);

$array = $icons = [];
foreach ($json as $icon_key => $icon) {
    echo "Icon " . $icon_key . " ";
    if (!isset($icon['familyStylesByLicense'])) {
        die("lỗi không có familyStylesByLicense\n");
    }
    if (empty($icon['familyStylesByLicense']['free']) or empty($icon['familyStylesByLicense']['free'][0])) {
        die("not free?\n");
    }
    if (!isset($icon['label'])) {
        die("lỗi không có label\n");
    }

    $label = $icon['label'];
    if (is_array($label)) {
        die('???');
    }
    if (!empty($icon['search']) and !empty($icon['search']['terms'])) {
        $label .= ' ' . implode(' ', $icon['search']['terms']);
    }

    if (empty($icon['familyStylesByLicense']['free'][0]['style'])) {
        die("lỗi không có style\n");
    }

    $class = 'fa-'  . $icon['familyStylesByLicense']['free'][0]['style'] . ' fa-' . $icon_key;
    echo "\033[0;32m" . $class . "\033[0m ";

    $array[] = [
        'id' => $class,
        'text' => $icon['label'],
        'search' => $label,
        'category' => isset($categories[$icon_key]) ? $categories[$icon_key]['title'] : 'Other'
    ];
    $icons[$class] = $icon['label'];

    echo "\n";
}

$file = NV_ROOTDIR . '/assets/fonts/fontawesome.json';
file_put_contents($file, json_encode($array), LOCK_EX);

$file = NV_ROOTDIR . '/includes/fontawesome.php';
$fileContent = "<?php\n\n" . NV_FILEHEAD . "\n\nif (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n\$fontawesome_icons = " . nv_var_export($array) . ";\n\n\$fontawesome_packs = " . nv_var_export($icons) . ";\n";
file_put_contents($file, $fileContent, LOCK_EX);

echo "Saved to " . $file . "\n";
