<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$listcid = $nv_Request->get_string('list', 'post,get');

if (!empty($listcid)) {
    $cid_array = explode(',', $listcid);
    $cid_array = array_map('intval', $cid_array);
    $listcid = implode(', ', $cid_array);

    // Duyệt các bình luận từ sau ra trước theo thứ tự pid
    $sql = 'SELECT cid, module, area, id, pid, attach FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid IN (' . $listcid . ') ORDER BY pid DESC';
    $comments = $db->query($sql)->fetchAll();
    $array_row_id = array();

    foreach ($comments as $row) {
        // Xác định các bài viết của các module tương ứng cần cập nhật lại sau khi xóa
        if (!isset($array_row_id[$row['module']])) {
            $array_row_id[$row['module']] = array();
        }
        $array_row_id[$row['module']][$row['id']] = array(
            'module' => $row['module'],
            'area' => $row['area'],
            'id' => $row['id']
        );

        // Xóa đính kèm
        if (!empty($row['attach'])) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach']);
        }

        if (defined('NV_IS_SPADMIN')) {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $row['cid']);
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET pid=' . $row['pid'] . ' WHERE pid=' . $row['cid']);
        } elseif (!empty($site_mod_comm)) {
            $array_mod_name = array();
            foreach ($site_mod_comm as $module_i => $row) {
                $array_mod_name[] = "'" . $module_i . "'";
            }
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $row['cid'] . ' AND module IN (' . implode(', ', $array_mod_name) . ')');
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET pid=' . $row['pid'] . ' WHERE pid=' . $row['cid'] . ' AND module IN (' . implode(', ', $array_mod_name) . ')');
        }

        nv_delete_notification(NV_LANG_DATA, $module_name, 'comment_queue', $row['cid']);
    }

    foreach ($array_row_id as $module => $rows) {
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

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edit_delete'], 'listcid ' . $listcid, $admin_info['userid']);

    echo $lang_module['delete_success'];
} else {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}
