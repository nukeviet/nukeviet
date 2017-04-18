<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$id_files = $nv_Request->get_int('id_files', 'get', 0);
$id_rows = $nv_Request->get_int('id_rows', 'get', 0);

if (empty($id_files) or empty($id_rows)) {
    die('NO');
}

$result = $db->query('SELECT path, download_groups FROM ' . $db_config['prefix'] . '_' . $module_data . '_files WHERE id=' . $id_files);
list($path, $download_groups) = $result->fetch(3);

if ($download_groups == '-1') {
    $download_groups = $pro_config['download_groups'];
}

if (nv_user_in_groups($download_groups)) {
    if (!empty($path)) {
        // Cap nhat luot download
        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_files_rows SET download_hits=download_hits+1 WHERE id_rows=' . $id_rows . ' AND id_files=' . $id_files);

        if (nv_is_url($path)) {
            Header('Location: ' . $path);
            die();
        } else {
            $download = new NukeViet\Files\Download(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload .'/files/'. $path, NV_UPLOADS_REAL_DIR);
            $download->download_file();
            exit();
        }
    }
} else {
    die('NO');
}
