<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9/9/2010, 6:38
 */

if (! defined('NV_IS_FILE_WEBTOOLS')) {
    die('Stop!!!');
}

$contents = 'Error Access!!!';

$checksess = $nv_Request->get_title('checksess', 'get', '');
if ($checksess == NV_CHECK_SESSION and file_exists(NV_ROOTDIR . '/install/update_data.php')) {
    $contents = '';
    $list_file_docs = nv_scandir(NV_ROOTDIR . '/install', '/^update_docs_([a-z]{2})\.html$/');

    // Xoa cac file docs
    foreach ($list_file_docs as $docsfile) {
        $check_del = nv_deletefile(NV_ROOTDIR . '/install/' . $docsfile);

        if ($check_del[0] == 0) {
            $contents .= $check_del[1] . ' ' . $lang_module['update_manual_delete'];
        }
    }

    // Xoa file du lieu nang cap
    $check_delete_file = nv_deletefile(NV_ROOTDIR . '/install/update_data.php');
    if ($check_delete_file[0] == 0) {
        $contents .= $check_delete_file[1] . ' ' . $lang_module['update_manual_delete'];
    }

    // Xoa thu muc file thay doi
    if (file_exists(NV_ROOTDIR . '/install/update')) {
        $check_delete_dir = nv_deletefile(NV_ROOTDIR . '/install/update', true);
        if ($check_delete_dir[0] == 0) {
            $contents .= $check_delete_dir[1] . ' ' . $lang_module['update_manual_delete'];
        }
    }

    // Xoa file log
    $list_file_logs = nv_scandir(NV_ROOTDIR . '/' . NV_DATADIR, '/^config_update_NVUD([A-Z0-9]+)\.php$/');
    foreach ($list_file_logs as $logsfile) {
        $check_del = nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/' . $logsfile);

        if ($check_del[0] == 0) {
            $contents .= $check_del[1] . ' ' . $lang_module['update_manual_delete'];
        }
    }

    clearstatcache();
    //Resets the contents of the opcode cache
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
}

if ($contents == '') {
    $contents = 'OK';
}

echo $contents;