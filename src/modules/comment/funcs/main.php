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

$module = $nv_Request->get_string('module', 'post,get');

// Kiểm tra module có được Sử dụng chức năng bình luận
if (!empty($module) and isset($module_config[$module]['activecomm'])) {
    require_once NV_ROOTDIR . '/modules/comment/comment.php';

    $area = $nv_Request->get_int('area', 'post,get', 0);
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $allowed_comm = $nv_Request->get_title('allowed', 'post,get', 0);
    $checkss = $nv_Request->get_title('checkss', 'post,get');
    $page = $nv_Request->get_int('page', 'get', 1);
    $status_comment = $nv_Request->get_title('status_comment', 'post,get', '');

    $comment_load = $nv_Request->get_int('comment_load', 'post,get', 0);

    if ($comment_load) {
        $content_comment = nv_comment_load($module, $checkss, $area, $id, $allowed_comm, $page, $status_comment);
    } else {
        $content_comment = nv_comment_module($module, $checkss, $area, $id, $allowed_comm, $page, $status_comment, 0);
    }

    if (!defined('NV_COMM_ID')) {
        $nv_BotManager->setNoIndex()->setFollow();
        $content_comment .= $nv_BotManager->getMetaTags(true);
    }
    include NV_ROOTDIR . '/includes/header.php';
    echo $content_comment;
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
