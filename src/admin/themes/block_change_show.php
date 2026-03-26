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
        // $list was already stripped of non-[0-9,] chars, building placeholders
        $array_bid = explode(',', $list);
        $placeholders = implode(',', array_fill(0, count($array_bid), '?'));
        $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act= ? WHERE bid IN (' . $placeholders . ')');
        $stmt_update->bindValue(1, $new_act, PDO::PARAM_INT);
        foreach ($array_bid as $k => $id) {
            $stmt_update->bindValue($k + 2, $id, PDO::PARAM_INT);
        }
        $stmt_update->execute();
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

$stmt = $db->prepare('SELECT bid, act FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (!empty($row) and md5(NV_CHECK_SESSION . '_' . $row['bid']) == $nv_Request->get_string('checkss', 'post')) {
    $act = $row['act'] ? 0 : 1;
    $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act= :act WHERE bid= :bid');
    $stmt_update->bindValue(':act', $act, PDO::PARAM_INT);
    $stmt_update->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
    $stmt_update->execute();
    $nv_Cache->delMod('themes');

    nv_jsonOutput(['status' => 'ok', 'act' => $act ? 'act' : 'deact']);
} else {
    nv_jsonOutput(['status' => 'error']);
}
