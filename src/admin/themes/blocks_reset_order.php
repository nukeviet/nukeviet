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

$checkss = $nv_Request->get_string('checkss', 'post');
$theme = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);

if (empty($theme) or $checkss !== md5($theme . NV_CHECK_SESSION)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Request params error!!!'
    ]);
}

// Lấy vị trí các block trong giao diện
$array_pos = array_column(nv_get_blocks($theme, false), 'tag');

// Cap nhat block hien thi toan site cho cac function moi phat sinh - Danh cho lap trinh vien
$array_bid = [];
// Danh sac tat ca cac block se kiem tra

$sth = $db->prepare('SELECT bid, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND all_func=1');
$sth->bindValue(':theme', $theme, PDO::PARAM_STR);
$sth->execute();

$stmt_del_group = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid = :bid');
$stmt_del_weight = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid = :bid');

while ($_row_bid = $sth->fetch()) {
    if (in_array($_row_bid['position'], $array_pos, true)) {
        $array_bid[$_row_bid['bid']] = $_row_bid['position'];
    } else {
        // Xóa các block không còn phần cấu hình.
        $stmt_del_group->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
        $stmt_del_group->execute();

        $stmt_del_weight->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
        $stmt_del_weight->execute();
    }
}
$sth->closeCursor();

$array_funcid = [];
// Danh sach ID tat ca cac function co block trong he thong
$result = $db->query('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC');
while ($_row_func = $result->fetch()) {
    $array_funcid[] = $_row_func['func_id'];
}
$result->closeCursor();

foreach ($array_bid as $bid => $position) {
    $func_list = [];
    // Cac fuction da them block
    $stmt_func = $db->prepare('SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid= :bid');
    $stmt_func->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt_func->execute();
    while ($_row_func = $stmt_func->fetch()) {
        $func_list[] = (int) $_row_func['func_id'];
    }
    $stmt_func->closeCursor();

    foreach ($array_funcid as $func_id) {
        if (!in_array((int) $func_id, $func_list, true)) {
            // Cac function chua duoc them

            $sth = $db->prepare('SELECT MAX(t1.weight)
                FROM ' . NV_BLOCKS_TABLE . '_weight t1
                INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
                WHERE t1.func_id = :func_id AND t2.theme = :theme AND t2.position = :position');
            $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
            $sth->bindValue(':func_id', $func_id, PDO::PARAM_INT);
            $sth->bindValue(':position', $position, PDO::PARAM_STR);
            $sth->execute();
            $weight = $sth->fetchColumn();

            $weight = (int) $weight + 1;

            $stmt_insert = $db->prepare('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (:bid, :func_id, :weight)');
            $stmt_insert->bindValue(':bid', $bid, PDO::PARAM_INT);
            $stmt_insert->bindValue(':func_id', $func_id, PDO::PARAM_INT);
            $stmt_insert->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_insert->execute();
        }
    }
}

// Cap nhat lai weight theo danh sach cac block
$array_position = [];

$sth = $db->prepare('SELECT bid, position, weight FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
$sth->bindValue(':theme', $theme, PDO::PARAM_STR);
$sth->execute();

$stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid');
while ($_row_pos = $sth->fetch()) {
    $array_position[] = $_row_pos['position'];
    $stmt_update->bindValue(':weight', $_row_pos['weight'], PDO::PARAM_INT);
    $stmt_update->bindValue(':bid', $_row_pos['bid'], PDO::PARAM_INT);
    $stmt_update->execute();
}
$sth->closeCursor();

// Kiem tra va cap nhat lai weight tung function
$array_position = array_unique($array_position);

foreach ($array_position as $position) {
    $func_id_old = $weight = 0;

    $sth = $db->prepare('SELECT t1.bid, t1.func_id
        FROM ' . NV_BLOCKS_TABLE . '_weight t1
        INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
        WHERE t2.theme= :theme AND t2.position = :position
        ORDER BY t1.func_id ASC, t1.weight ASC');
    $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
    $sth->bindValue(':position', $position, PDO::PARAM_STR);
    $sth->execute();
    
    $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid AND func_id= :func_id');
    while ($_row_weight = $sth->fetch()) {
        if ($_row_weight['func_id'] == $func_id_old) {
            ++$weight;
        } else {
            $weight = 1;
            $func_id_old = $_row_weight['func_id'];
        }

        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $_row_weight['bid'], PDO::PARAM_INT);
        $stmt_update->bindValue(':func_id', $_row_weight['func_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $sth->closeCursor();
}

nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('block_weight'), 'reset position all block', $admin_info['userid']);
$nv_Cache->delMod('themes');

$db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups');
$db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');

nv_jsonOutput([
    'success' => 1,
    'text' => $nv_Lang->getModule('block_update_success')
]);
