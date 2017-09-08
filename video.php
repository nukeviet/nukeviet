<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC.
 * All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (isset($_GET['response_headers_detect'])) {
    exit(0);
}

define('NV_ADMIN', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
if ($sys_info['ini_set_support']) {
    echo 'set_time_limit 3000<br>';
    set_time_limit(3000);
}

try {

    // Duyệt tất cả các ngôn ngữ
    $language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
    while (list ($lang) = $language_query->fetch(3)) {
        // Lấy tất cả các module và module ảo của nó
        $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'videoclips'");
        while (list ($mod, $mod_data) = $mquery->fetch(3)) {
            echo $lang . '--> ' . $mod . '<br>';

            /* Thêm cấu hình sắp xếp:
             - otherClipsNum = 16: Số video hiển thị trên một trang
             - playerAutostart = 0: Tùy chọn autoplay video
             - playerSkin: giao diện
             - playerMaxWidth: chiều rộng của video
             */
            $configMods['idhomeclips'] = 0;
            if (file_exists(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $mod . ".php")) {
                require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $mod . ".php");
            }
            $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'otherClipsNum', '" . $configMods['otherClipsNum'] . "')");
            $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'playerAutostart', '" . $configMods['playerAutostart'] . "')");
            $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'playerSkin', '" . $configMods['playerSkin'] . "')");
            $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $mod . "', 'playerMaxWidth', '" . $configMods['playerMaxWidth'] . "')");
            $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET `config_value` = '" . $configMods['idhomeclips'] . "' WHERE `lang` = '" . $lang . "' AND  `module` = '" . $mod . "' AND `config_name` = 'idhomeclips'");

            /*
             * Xóa file thừa sinh ra do cấu hình vào file
             */
            @nv_deletefile(NV_ROOTDIR . '/data/config/config_module-' . $mod . '.php');
        }
    }

} catch (PDOException $e) {
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    die($e->getMessage());
}
die('Thực hiện xong');