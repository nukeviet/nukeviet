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

$bid = $nv_Request->get_int('bid', 'post');
$checkss = $nv_Request->get_string('checkss', 'post');
$stmt = $db->prepare('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (!($row['bid'] > 0 and (md5($row['theme'] . NV_CHECK_SESSION) == $checkss or md5(NV_CHECK_SESSION . '_' . $row['bid']) == $checkss))) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Request params error!!!'
    ]);
}
$theme = $row['theme'];
$position = $row['position'];

$stmt_del = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt_del->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt_del->execute();

$stmt_del_w = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid= :bid');
$stmt_del_w->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt_del_w->execute();

// reupdate
$weight = 0;
$sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC');
$sth->bindValue(':theme', $theme, PDO::PARAM_STR);
$sth->bindValue(':position', $position, PDO::PARAM_STR);
$sth->execute();

$stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight= :weight WHERE bid= :bid');
while ($_row_bid = $sth->fetch()) {
    ++$weight;
    $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt_update->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
    $stmt_update->execute();
}
$sth->closeCursor();

$func_id_old = $weight = 0;
$sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2
    ON t1.bid = t2.bid
    WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC');
$sth->bindValue(':theme', $theme, PDO::PARAM_STR);
$sth->bindValue(':position', $position, PDO::PARAM_STR);
$sth->execute();

$stmt_update_w = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid AND func_id= :func_id');
while ($_row_weight = $sth->fetch()) {
    if ($_row_weight['func_id'] == $func_id_old) {
        ++$weight;
    } else {
        $weight = 1;
        $func_id_old = $_row_weight['func_id'];
    }

    $stmt_update_w->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt_update_w->bindValue(':bid', $_row_weight['bid'], PDO::PARAM_INT);
    $stmt_update_w->bindValue(':func_id', $_row_weight['func_id'], PDO::PARAM_INT);
    $stmt_update_w->execute();
}
$sth->closeCursor();

$nv_Cache->delMod('themes');

nv_jsonOutput([
    'success' => 1,
    'text' => $nv_Lang->getModule('block_delete_success')
]);
