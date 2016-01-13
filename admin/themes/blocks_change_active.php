<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$list = $nv_Request->get_string('list', 'post,get');
$array_bid = explode(',', $list);
if (! empty($array_bid)) {
    $array_bid = array_map('intval', $array_bid);

    $list = $nv_Request->get_string('active_device', 'post,get');

    $array_active_device = explode(',', $list);
    $array_active_device = array_map('intval', $array_active_device);
    if (in_array('1', $array_active_device) or (in_array('2', $array_active_device) and in_array('3', $array_active_device) and in_array('4', $array_active_device))) {
        $active = 1;
    } else {
        $active = implode(',', $array_active_device);
    }

    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET active=' . $db->quote($active) . ' WHERE bid in (' . implode(',', $array_bid) . ')');
    $nv_Cache->delMod('themes');

    echo $lang_module['block_update_success'];
} else {
    echo $lang_module['block_error_noblock'];
}