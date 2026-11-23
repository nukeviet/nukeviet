<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

$cid = $nv_Request->get_int('cid', 'get');
$tokend = $nv_Request->get_string('tokend', 'get');

if ($cid > 0 and hash_equals(md5($cid . '_' . NV_CHECK_SESSION), $tokend)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid;
    $row = $db->query($sql)->fetch();

    // Kiểm tra quyền tải file đính kèm
    $is_allowed = false;
    if (!empty($row) and isset($module_config[$row['module']])) {
        $is_admin_comm = false;
        if (defined('NV_IS_SPADMIN')) {
            $is_admin_comm = true;
        } elseif (defined('NV_IS_MODADMIN')) {
            $adminscomm = array_map('intval', explode(',', $module_config[$row['module']]['adminscomm']));
            if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
                $is_admin_comm = true;
            }
        }

        if ($is_admin_comm) {
            // Người quản lý bình luận được tải mọi file
            $is_allowed = true;
        } elseif ($row['status'] == 1 and !empty($module_config[$row['module']]['activecomm']) and nv_user_in_groups($module_config[$row['module']]['view_comm'])) {
            // Người khác chỉ được tải file của bình luận đã duyệt, module bật bình luận và có quyền xem bình luận
            $is_allowed = true;
        }
    }

    if ($is_allowed and !empty($row['attach']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'])) {
        $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, preg_replace('/^(.*)\.(.*)\.(.*)$/', '\\1.\\3', basename($row['attach'])), true, 0);
        $download->download_file();
        exit();
    }
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
