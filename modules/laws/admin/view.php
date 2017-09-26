<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

//$check_permission = false;
$check_permission = true;
$rowcontent['id'] = $nv_Request->get_int('id', 'get,post', 0);
if ($rowcontent['id'] > 0) {
    $rowcontent = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row where id=' . $rowcontent['id'])->fetch();
    if (! empty($rowcontent['id'])) {
        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_permission = true;
        } else {
            $check_comments = 0;
        }
    }
}
if ($check_permission) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail/' . $rowcontent['alias']);
} else {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'], 404);
}