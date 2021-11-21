<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$catid = $nv_Request->get_int('catid', 'post', 0);
$mod = $nv_Request->get_string('mod', 'post', '');
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
$content = 'NO_' . $catid;

list ($catid) = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE id=' . $catid)->fetch(3);
if ($catid > 0) {
    if ($mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 20) {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET numlink=' . $new_vid . ' WHERE id=' . $catid;
        $db->query($sql);
        $content = 'OK';
    }
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';