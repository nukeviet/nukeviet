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

if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Error session!!!'
    ]);
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['delete_dir']) or $check_allow_upload_dir['delete_dir'] !== true) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

if (empty($path) or $path == NV_UPLOADS_DIR) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

$d = nv_deletefile(NV_ROOTDIR . '/' . $path, true);
if ($d[0]) {
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/([a-z0-9\-\_\/]+)$/i', $path, $m)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1], true);
        @nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1], true);
    }

    $result = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . "_dir WHERE dirname='" . $path . "' OR dirname LIKE '" . $path . "/%'");
    while ([$did] = $result->fetch(3)) {
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did);
        $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE did = ' . $did);
    }

    nv_dirListRefreshSize();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('deletefolder'), $path, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success'
    ]);
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => $d[1]
]);
