<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

$cid = $nv_Request->get_int('cid', 'get');
$tokend = $nv_Request->get_string('tokend', 'get');

if ($tokend == md5($cid . '_' . NV_CHECK_SESSION)) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid;
    $row = $db->query($sql)->fetch();
    if (!empty($row['attach']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'])) {
        $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, preg_replace('/^(.*)\.(.*)\.(.*)$/', '\\1.\\3', basename($row['attach'])), true, 0);
        $download->download_file();
        exit();
    }
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
