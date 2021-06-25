<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

if (!empty($id) and md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $id) == $nv_Request->get_string('checkss', 'get')) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_atc', 'id ' . $id, $admin_info['userid']);

    $sql = 'SELECT act FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND (is_sys=0 OR act=0)';
    $row = $db->query($sql)->fetch();

    if (!empty($row)) {
        $act = (int) ($row['act']);
        $new_act = (!empty($act)) ? 0 : 1;
        $db->query('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $new_act . ' WHERE id=' . $id);
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs');
