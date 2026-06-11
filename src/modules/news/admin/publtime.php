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

use NukeViet\Module\news\Shared\Logs;

$checkss = $nv_Request->get_string('checkss', 'get');
$action_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_action';

if (!empty($checkss) and csrf_check($checkss, $action_csrf_key)) {
    $listid = $nv_Request->get_string('listid', 'get');
    $id_array = array_map('intval', explode(',', $listid));

    $publ_array = [];

    $sql = 'SELECT id, listcatid, status, publtime, exptime FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        if ($_row['status'] != 4 and $_row['status'] <= $global_code_defined['row_locked_status']) {
            $arr_catid = explode(',', $_row['listcatid']);

            $check_permission = false;
            if (defined('NV_IS_ADMIN_MODULE')) {
                $check_permission = true;
            } else {
                $check_edit = 0;
                foreach ($arr_catid as $catid_i) {
                    if (isset($array_cat_admin[$admin_id][$catid_i])) {
                        if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                            ++$check_edit;
                        }
                    }
                }
                if ($check_edit == count($arr_catid)) {
                    $check_permission = true;
                }
            }

            if ($check_permission > 0) {
                $data_save = [];
                if ($_row['exptime'] > 0 and $_row['exptime'] < NV_CURRENTTIME) {
                    $data_save['exptime'] = 0;
                }
                if ($_row['publtime'] > NV_CURRENTTIME) {
                    $data_save['publtime'] = NV_CURRENTTIME;
                }
                if ($_row['status'] != 1) {
                    $data_save['status'] = 1;
                }

                if (!empty($data_save)) {
                    $s_ud = [];
                    $binds = [];
                    foreach ($data_save as $key => $value) {
                        $s_ud[] = $key . ' = :' . $key;
                        $binds[':' . $key] = $value;
                    }
                    $s_ud[] = 'edittime = ' . NV_CURRENTTIME;

                    $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . implode(', ', $s_ud) . ' WHERE id = :id');
                    foreach ($binds as $key => $value) {
                        $stmt_update->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                    }
                    $stmt_update->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                    $stmt_update->execute();

                    foreach ($arr_catid as $catid_i) {
                        $stmt_update_cat = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . intval($catid_i) . ' SET ' . implode(', ', $s_ud) . ' WHERE id = :id');
                        foreach ($binds as $key => $value) {
                            $stmt_update_cat->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                        }
                        $stmt_update_cat->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                        $stmt_update_cat->execute();
                    }
                    $publ_array[] = $_row['id'];

                    // Lưu log thay đổi trạng thái của bài viết
                    Logs::saveLogStatusPost($_row['id'], 1);
                }
            }
        }
    }
    $result->closeCursor();
    if (!empty($publ_array)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_publ_content', 'listid: ' . implode(', ', $publ_array), $admin_info['userid']);
    }
    nv_set_status_module();
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
