<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

$cid = $nv_Request->get_int('cid', 'get');
$tokend = $nv_Request->get_string('tokend', 'get');

if ($tokend == md5($cid . '_' . NV_CHECK_SESSION)) {
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid = :cid');
    $stmt->bindValue(':cid', $cid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    // Chỉ tải được đính kèm của bình luận đã duyệt, thuộc đối tượng mà người dùng được xem
    if (!empty($row['attach']) and $row['status'] == 1) {
        require_once NV_ROOTDIR . '/modules/comment/comment.php';
        if (nv_comment_allowed($row['module'], $row['area'], $row['id']) === null or !nv_user_in_groups($module_config[$row['module']]['view_comm'])) {
            $row['attach'] = '';
        }
    } else {
        $row['attach'] = '';
    }

    if (!empty($row['attach']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'])) {
        $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, preg_replace('/^(.*)\.(.*)\.(.*)$/', '\\1.\\3', basename($row['attach'])), true, 0);
        $download->download_file();
        exit();
    }
}

nv_error404();
