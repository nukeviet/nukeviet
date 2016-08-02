<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_MOD_2STEP_VERIFICATION')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$tokend_confirm_password = $nv_Request->get_title(NV_BRIDGE_USER_MODULE . '_confirm', 'session', '');
$tokend = 'confirm_pass_' . NV_CHECK_SESSION;

if ($tokend_confirm_password != $tokend) {
    $contents = 'CONFIRM-' . $secretkey;
} else {
    $contents = 'KEY-' . $secretkey;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
