<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

// Bật/tắt hàng loạt
if ($nv_Request->isset_request('multi, list, checkss', 'post')) {
    $new_act = (int) $nv_Request->get_bool('multi', 'post', false);
    $list = $nv_Request->get_string('list', 'post');
    $checkss = $nv_Request->get_string('checkss', 'post');

    $selectthemes = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);
    if (!empty($list) and md5($selectthemes . NV_CHECK_SESSION) == $checkss) {
        $list = preg_replace('/[^0-9\,]+/', '', $list);
        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act=' . $new_act . ' WHERE bid IN (' . $list . ')');
        $nv_Cache->delMod('themes');
        nv_jsonOutput([
            'status' => 'OK',
            'act' => $new_act ? 'act' : 'deact'
        ]);
    }

    nv_jsonOutput([
        'status' => 'error'
    ]);
}

$bid = $nv_Request->get_int('bid', 'post');

[$bid, $act] = $db->query('SELECT bid, act FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch(3);

if ((int) $bid > 0 and md5(NV_CHECK_SESSION . '_' . $bid) == $nv_Request->get_string('checkss', 'post')) {
    $act = $act ? 0 : 1;
    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act=' . $act . ' WHERE bid=' . $bid);
    $nv_Cache->delMod('themes');

    nv_jsonOutput(['status' => 'ok', 'act' => $act ? 'act' : 'deact']);
} else {
    nv_jsonOutput(['status' => 'error']);
}
