<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
$id = $nv_Request->get_int('id', 'post,get');

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (!empty($row)) {
    if (!empty($row['file_name']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'])) {
        nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'], false);
    }

    if (!empty($row['imageforswf']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'])) {
        nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'], false);
    }
    $sql = 'DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
    $db->query($sql);

    $sql = 'DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid=' . $id;
    $db->query($sql);
    nv_fix_banner_weight($row['pid']);
    $nv_Cache->delMod($module_name);
    nv_CreateXML_bannerPlan();

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_banner', 'bannerid ' . $id, $admin_info['userid']);
    if (defined('NV_IS_AJAX')) {
        echo $lang_module['delfile_success'];
    } else {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=banners_list');
    }
} else {
    echo $lang_module['delfile_error'];
}
