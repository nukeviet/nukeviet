<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if (! defined('NV_IS_MOD_COMMENT')) {
    die('Stop!!!');
}

$module = $nv_Request->get_string('module', 'post,get');

// Kiểm tra module có được Sử dụng chức năng bình luận
if (! empty($module) and isset($module_config[$module]['activecomm'])) {
    $area = $nv_Request->get_int('area', 'post,get', 0);
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $allowed_comm = $nv_Request->get_title('allowed', 'post,get', 0);
    $checkss = $nv_Request->get_title('checkss', 'post,get');
    $page = $nv_Request->get_int('page', 'get', 1);
    $status_comment = $nv_Request->get_title('status_comment', 'post,get', '');
    require_once NV_ROOTDIR . '/modules/comment/comment.php';
    $content_comment = nv_comment_module($module, $checkss, $area, $id, $allowed_comm, $page, $status_comment, 0);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content_comment;
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);