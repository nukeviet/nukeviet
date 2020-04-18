<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$bid = $nv_Request->get_int('bid', 'post');

list($bid, $act) = $db->query('SELECT bid, act FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch(3);

if (intval($bid) > 0) {
    $act = $act ? 0 : 1;
    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act=' . $act . ' WHERE bid=' . $bid);
    $nv_Cache->delMod('themes');

    nv_jsonOutput(array( 'status' => 'ok', 'act' => $act ? 'act' : 'deact' ));
} else {
    nv_jsonOutput(array( 'status' => 'error' ));
}