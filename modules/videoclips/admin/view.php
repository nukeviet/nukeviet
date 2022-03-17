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

$id = $nv_Request->get_int('id', 'get,post', 0);

if ($id > 0) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a,
    " . NV_PREFIXLANG . "_" . $module_data . "_hit b
    WHERE a.id=" . $id . "
    AND a.status=1 AND a.id=b.cid LIMIT 1";
    $result = $db->query($sql);
    $num = $result->rowCount();
    if ($num == 1) {
        $clip = $result->fetch();
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=video-' . $clip['alias']);
    }
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'], 404);
