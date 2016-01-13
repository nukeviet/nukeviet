<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 30 Nov 2014 01:54:12 GMT
 */

if (!defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$theme = $nv_Request->get_title('theme', 'post', '', 1);
if (empty($theme) or !(preg_match($global_config['check_theme'], $theme) or preg_match($global_config['check_theme_mobile'], $theme))) {
    die();
}
try {
    $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0 AND theme= :theme');
    $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn() and $global_config['site_theme'] != $theme) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['theme_delete'], 'theme ' . $theme, $admin_info['userid']);

        if (preg_match($global_config['check_theme_mobile'], $theme)) {
            $sth = $db->prepare("UPDATE " . NV_MODULES_TABLE . " SET mobile='' WHERE mobile = :theme");
        } else {
            $sth = $db->prepare("UPDATE " . NV_MODULES_TABLE . " SET theme='' WHERE theme = :theme");
        }
        $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_modthemes WHERE theme = :theme');
        $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_blocks_weight WHERE bid IN (SELECT bid FROM ' . NV_PREFIXLANG . '_blocks_groups WHERE theme= :theme)');
        $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_blocks_groups WHERE theme = :theme');
        $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delMod('themes');
        $nv_Cache->delMod('modules');

        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_modthemes');
        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_blocks_weight');
        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_blocks_groups');

        echo $lang_module['theme_delete_success'];
    } else {
        echo $lang_module['theme_delete_unsuccess'];
    }
} catch (PDOException $e) {
    die($e->getMessage());
}