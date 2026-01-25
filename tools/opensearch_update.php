<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

if (!defined('NV_IS_GODADMIN')) {
    exit("Not allowed");
}

// Lấy tất cả ngôn ngữ đã cài đặt
$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1 ORDER BY weight ASC';
$array_sitelangs = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

foreach ($array_sitelangs as $lang) {
    $sql = "SELECT config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . $lang . "' AND module='global' AND config_name='opensearch_link'";
    $opensearch_link = $db->query($sql)->fetchColumn() ?: '';

    $opensearch_link = json_decode($opensearch_link, true);
    if (!is_array($opensearch_link)) {
        $opensearch_link = [];
    }
    if (empty($opensearch_link)) {
        continue;
    }

    $new_value = [];
    foreach ($opensearch_link as $mod => $values) {
        // Nếu đã là dạng mới rồi thì không xử lý nữa
        if (isset($values['active'])) {
            continue 2;
        }
        // Toàn site không dùng chữ site vì có thể trùng module khác
        if ($mod == 'site') {
            $mod = '_site';
        }

        $new_value[$mod] = [
            'active' => 1,
            'shortname' => $values[0] ?? '',
            'description' => $values[1] ?? '',
        ];
    }

    $sql = "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value=" . $db->quote(json_encode($new_value)) . "
    WHERE lang='" . $lang . "' AND module='global' AND config_name='opensearch_link'";
    $db->query($sql);
}

$nv_Cache->delAll(true);

echo "Success!";
