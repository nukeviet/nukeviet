<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_main';
if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    if (defined('NV_IS_AJAX')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
}

// Xóa banner
$listid = $nv_Request->get_typed_array('listid', 'post', 'int', []);
if (empty($listid)) {
    $listid = [$nv_Request->get_int('id', 'post,get')];
}
$listid = array_unique(array_filter(array_map('intval', $listid)));

$array_rows = [];
if (!empty($listid)) {
    $result = $db->query('SELECT id, pid, file_name, imageforswf FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id IN (' . implode(',', $listid) . ')');
    while ($row = $result->fetch()) {
        $array_rows[$row['id']] = $row;
    }
    $result->closeCursor();
}

if (!empty($array_rows)) {
    $stmt_del_row = $db->prepare('DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :id');
    $stmt_del_click = $db->prepare('DELETE FROM ' . NV_BANNERS_GLOBALTABLE . '_click WHERE bid = :id');
    $array_pids = [];

    foreach ($array_rows as $id => $row) {
        if (!empty($row['file_name']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'])) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'], false);
        }

        if (!empty($row['imageforswf']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'])) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'], false);
        }

        $stmt_del_row->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt_del_row->execute();

        $stmt_del_click->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt_del_click->execute();

        $array_pids[$row['pid']] = $row['pid'];
    }

    foreach ($array_pids as $pid) {
        nv_fix_banner_weight($pid);
    }
    $nv_Cache->delMod($module_name);
    nv_CreateXML_bannerPlan();

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_banner', 'bannerid ' . implode(',', array_keys($array_rows)), $admin_info['userid']);

    if (defined('NV_IS_AJAX')) {
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getModule('delfile_success'),
            'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true)
        ]);
    } else {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    }
} else {
    if (defined('NV_IS_AJAX')) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('delfile_error')]);
    }
}
