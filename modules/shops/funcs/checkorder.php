<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$checkss = $nv_Request->get_title('checkss', 'post', '');
$order_id = $nv_Request->get_int('id', 'post', 0);

if ($checkss == md5($order_id . $global_config['sitekey'] . session_id())) {

}
if (!empty($array_update_order)) {
    $title = sprintf($lang_module['update_order'], implode(", ", $array_update_order));
    $title = str_replace("_", "#@#", $title);

    $contents = "UPDATE_" . $title;
} else {
    $title = str_replace("_", "#@#", $lang_module['no_update_order']);
    $contents = "NOUPDATE_" . $title;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
