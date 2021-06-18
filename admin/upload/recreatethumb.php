<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['create_dir']) or $check_allow_upload_dir['create_dir'] !== true) {
    nv_htmlOutput('ERROR_' . $lang_module['notlevel']);
}
if (empty($path)) {
    nv_htmlOutput('ERROR_' . $lang_module['notlevel']);
}

nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['recreatethumb'], $path, $admin_info['userid']);
$_array_filename = array();
$idf = $nv_Request->get_int('idf', 'post', -1);
if ($idf < 0) {
    //Đọc tất cả các thư mục, thư mục con của thư mục.
    if ($sys_info['allowed_set_time_limit']) {
        set_time_limit(0);
    }

    $_dirlist = array();
    $_dirlist = nv_listUploadDir($path, $_dirlist);

    // Tìm tất cả các file ảnh có thể tạp ảnh thum để tạo lại
    foreach ($_dirlist as $pathimg) {
        if ($dh = opendir(NV_ROOTDIR . '/' . $pathimg)) {
            while (($file = readdir($dh)) !== false) {
                $fileName = $pathimg . '/' . $file;
                if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp)))$/i', $fileName, $m)) {
                    $_array_filename[] = $fileName;
                }
            }
        }
    }
    $number_file = sizeof($_array_filename);
    if ($number_file > 0) {
        $content_config = "<?php" . "\n\n";
        $content_config .= NV_FILEHEAD . "\n\n";
        $content_config .= "if (!defined('NV_IS_FILE_ADMIN'))\n    die('Stop!!!');\n\n";
        $content_config .= "\$_array_filename=" . var_export($_array_filename, true) . ";\n";
        $listfile = file_put_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/recreatethumb_' . md5($path . '_' . NV_CHECK_SESSION) . '.php', trim($content_config), LOCK_EX);
        nv_htmlOutput('OK_0_' . $number_file);
    } else {
        nv_htmlOutput('COMPLETE_0');
    }
} elseif (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/recreatethumb_' . md5($path . '_' . NV_CHECK_SESSION) . '.php')) {
    if ($sys_info['allowed_set_time_limit']) {
        set_time_limit(0);
    }

    include_once NV_ROOTDIR . '/' . NV_TEMP_DIR . '/recreatethumb_' . md5($path . '_' . NV_CHECK_SESSION) . '.php';
    $number_file = sizeof($_array_filename);

    // Duyệt mối lần 20 file để tránh : số lượng file quá nhiều bị time out
    $idf_next = $idf + 20;
    if ($idf_next > $number_file) {
        $idf_next = $number_file;
    }
    for ($i = $idf; $i < $idf_next; $i++) {
        nv_get_viewImage($_array_filename[$i], 1);
    }

    if ($idf < $number_file) {
        nv_htmlOutput('OK_' . $idf_next . '_' . $number_file . '_' . $path);
    } else {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/recreatethumb_' . md5($path . '_' . NV_CHECK_SESSION) . '.php');
        nv_htmlOutput('COMPLETE_' . $number_file);
    }
}

nv_htmlOutput('ERROR_' . $lang_module['folder_exists']);
