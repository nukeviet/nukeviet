<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$listcid = $nv_Request->get_string('list', 'post,get');
$checkss = $nv_Request->get_string('checkss', 'post', '');
if ($checkss != md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid']) || empty($listcid)) {
    nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_code_11')]);
}

$cid_array = explode(',', $listcid);
$cid_array = array_map('intval', $cid_array);
$listcid = implode(', ', $cid_array);

// Duyệt các bình luận từ sau ra trước theo thứ tự pid
$sql = 'SELECT cid, module, area, id, pid, attach FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid IN (' . $listcid . ') ORDER BY pid DESC';
$comments = $db->query($sql)->fetchAll();
$array_row_id = [];

foreach ($comments as $row) {
    // Xác định các bài viết của các module tương ứng cần cập nhật lại sau khi xóa
    if (!isset($array_row_id[$row['module']])) {
        $array_row_id[$row['module']] = [];
    }
    $array_row_id[$row['module']][$row['id']] = [
        'module' => $row['module'],
        'area' => $row['area'],
        'id' => $row['id']
    ];

    // Xóa đính kèm
    if (!empty($row['attach'])) {
        nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach']);
    }

    if (defined('NV_IS_SPADMIN')) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $row['cid']);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET pid=' . $row['pid'] . ' WHERE pid=' . $row['cid']);
    } elseif (!empty($site_mod_comm)) {
        $array_mod_name = [];
        foreach ($site_mod_comm as $module_i => $row) {
            $array_mod_name[] = "'" . $module_i . "'";
        }
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $row['cid'] . ' AND module IN (' . implode(', ', $array_mod_name) . ')');
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET pid=' . $row['pid'] . ' WHERE pid=' . $row['cid'] . ' AND module IN (' . implode(', ', $array_mod_name) . ')');
    }

    nv_delete_notification(NV_LANG_DATA, $module_name, 'comment_queue', $row['cid']);
}

foreach ($array_row_id as $rows) {
    foreach ($rows as $row) {
        if (isset($site_mod_comm[$row['module']])) {
            $mod_info = $site_mod_comm[$row['module']];
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php')) {
                include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
                $nv_Cache->delMod($row['module']);
            }
        }
    }
}

nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('edit_delete'), 'listcid ' . $listcid, $admin_info['userid']);

nv_jsonOutput(['status' => 'ok', 'mess' => $nv_Lang->getModule('delete_success')]);
