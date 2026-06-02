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
if (!empty($checkss) and csrf_check($checkss, $csrf_key)) {
    $listid = $nv_Request->get_string('listid', 'get');
    $id_array = array_map('intval', explode(',', $listid));

    $exp_array = [];
    $sql = 'SELECT id, listcatid, admin_id, publtime, exptime, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        if (($_row['exptime'] == 0 or $_row['exptime'] > NV_CURRENTTIME) and $_row['status'] != 4 and $_row['status'] <= $global_code_defined['row_locked_status']) {
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
                        } else {
                            if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                                ++$check_edit;
                            } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($_row['status'] == 0 or $_row['status'] == 2)) {
                                ++$check_edit;
                            } elseif ($_row['status'] == 0 and $_row['admin_id'] == $admin_id) {
                                ++$check_edit;
                            } elseif ($_row['status'] == 2) {
                                ++$check_edit;
                            }
                        }
                    }
                }
                if ($check_edit == count($arr_catid)) {
                    $check_permission = true;
                }
            }
            if ($check_permission > 0) {
                $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = 6 WHERE id = :id');
                $stmt_update->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                $stmt_update->execute();

                foreach ($arr_catid as $catid_i) {
                    $stmt_update_cat = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . intval($catid_i) . ' SET status = 6 WHERE id = :id');
                    $stmt_update_cat->bindValue(':id', $_row['id'], PDO::PARAM_INT);
                    $stmt_update_cat->execute();
                }
                $exp_array[] = $_row['id'];

                // Lưu log thay đổi trạng thái bài viết
                Logs::saveLogStatusPost($_row['id'], 6);
            }
        }
    }
    $result->closeCursor();

    if (!empty($exp_array)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_declined_content', 'listid: ' . implode(', ', $exp_array), $admin_info['userid']);
    }
    nv_set_status_module();
}
nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
