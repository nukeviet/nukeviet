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

use NukeViet\Module\news\Shared\Logs;

if ($nv_Request->isset_request('checkss', 'get') and $nv_Request->get_string('checkss', 'get') == NV_CHECK_SESSION) {
    $listid = $nv_Request->get_string('listid', 'get');
    $id_array = array_map('intval', explode(',', $listid));

    $exp_array = [];
    $sql = 'SELECT id, listcatid, publtime, exptime, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id in (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);
    while (list($id, $listcatid, $publtime, $exptime, $status) = $result->fetch(3)) {
        if (($exptime == 0 or $exptime > NV_CURRENTTIME) and $status != 4 and $status <= $global_code_defined['row_locked_status']) {
            $arr_catid = explode(',', $listcatid);

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
                            } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status = 2)) {
                                ++$check_edit;
                            } elseif ($status == 0 and $post_id == $admin_id) {
                                ++$check_edit;
                            } elseif ($status == 2) {
                                ++$check_edit;
                            }
                        }
                    }
                }
                if ($check_edit == sizeof($arr_catid)) {
                    $check_permission = true;
                }
            }
            if ($check_permission > 0) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = 5 WHERE id =' . $id);
                foreach ($arr_catid as $catid_i) {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status = 5 WHERE id =' . $id);
                }
                $exp_array[] = $id;

                // Lưu log thay đổi trạng thái của bài viết
                Logs::saveLogStatusPost($id, 5);
            }
        }
    }

    if (!empty($exp_array)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_wait_content', 'listid: ' . implode(', ', $exp_array), $admin_info['userid']);
    }
    nv_set_status_module();
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
