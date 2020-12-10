<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (! isset($check_allow_upload_dir['delete_dir']) or $check_allow_upload_dir['delete_dir'] !== true) {
    die('ERROR_' . $lang_module['notlevel']);
}

if (empty($path) or $path == NV_UPLOADS_DIR) {
    die('ERROR_' . $lang_module['notlevel']);
}

$d = nv_deletefile(NV_ROOTDIR . '/' . $path, true);
if ($d[0]) {
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/([a-z0-9\-\_\/]+)$/i', $path, $m)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1], true);
    }

    $result = $db->query("SELECT did FROM " . NV_UPLOAD_GLOBALTABLE . "_dir WHERE dirname='" . $path . "' OR dirname LIKE '" . $path . "/%'");
    while (list($did) = $result->fetch(3)) {
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = ' . $did);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['deletefolder'], $path, $admin_info['userid']);
    echo 'OK';
} else {
    die('ERROR_' . $d[1]);
}
