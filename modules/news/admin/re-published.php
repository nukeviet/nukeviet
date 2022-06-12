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

    $publ_array = [];

    $sql = 'SELECT id, listcatid, status, publtime, exptime FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id in (' . implode(',', $id_array) . ')';
    $result = $db->query($sql);
    while (list($id, $listcatid, $status, $publtime, $exptime) = $result->fetch(3)) {
        if ($status != 4 and $status <= $global_code_defined['row_locked_status']) {
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
                        }
                    }
                }
                if ($check_edit == sizeof($arr_catid)) {
                    $check_permission = true;
                }
            }

            if ($check_permission > 0) {
                $data_save = [];
                if ($exptime > 0 and $exptime < NV_CURRENTTIME) {
                    $data_save['exptime'] = 0;
                }
                $data_save['publtime'] = NV_CURRENTTIME;
                if ($status != 1) {
                    $data_save['status'] = 1;
                }

                if (!empty($data_save)) {
                    $s_ud = '';
                    foreach ($data_save as $key => $value) {
                        $s_ud .= $key . " = '" . $value . "', ";
                    }
                    $s_ud .= "edittime = '" . NV_CURRENTTIME . "'";
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET ' . $s_ud . ' WHERE id =' . $id);
                    foreach ($arr_catid as $catid_i) {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET ' . $s_ud . ' WHERE id =' . $id);
                    }
                    $publ_array[] = $id;

                    // Lưu log thay đổi trạng thái bài viết
                    if (isset($data_save['status'])) {
                        Logs::saveLogStatusPost($id, 1);
                    }
                }
            }
        }
    }
    if (!empty($publ_array)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_re_publ_content', 'listid: ' . implode(', ', $publ_array), $admin_info['userid']);
    }
    nv_set_status_module();
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
