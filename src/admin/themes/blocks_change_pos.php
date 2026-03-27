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
$pos_new = nv_unhtmlspecialchars($nv_Request->get_title('pos', 'post', '', 0));

$stmt = $db->prepare('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (!empty($row) and md5($row['theme'] . NV_CHECK_SESSION) == $nv_Request->get_string('checkss', 'post,get')) {
    $theme = $row['theme'];
    $pos_old = $row['position'];

    $sth = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET position= :position, weight=8388607 WHERE bid= :bid');
    $sth->bindValue(':position', $pos_new, PDO::PARAM_STR);
    $sth->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
    $sth->execute();

    $stmt_w = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=8388607 WHERE bid= :bid');
    $stmt_w->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
    $stmt_w->execute();

    //Update weight for old position
    $sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC');
    $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
    $sth->bindValue(':position', $pos_old, PDO::PARAM_STR);
    $sth->execute();

    $weight = 0;
    $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight= :weight WHERE bid= :bid');
    while ($_row_bid = $sth->fetch()) {
        ++$weight;
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $sth->closeCursor();

    if ($weight) {
        $func_id_old = $weight = 0;
        $sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC');
        $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
        $sth->bindValue(':position', $pos_old, PDO::PARAM_STR);
        $sth->execute();

        $stmt_update2 = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid AND func_id= :func_id');
        while ($_row_weight = $sth->fetch()) {
            if ($_row_weight['func_id'] == $func_id_old) {
                ++$weight;
            } else {
                $weight = 1;
                $func_id_old = $_row_weight['func_id'];
            }

            $stmt_update2->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update2->bindValue(':bid', $_row_weight['bid'], PDO::PARAM_INT);
            $stmt_update2->bindValue(':func_id', $_row_weight['func_id'], PDO::PARAM_INT);
            $stmt_update2->execute();
        }
        $sth->closeCursor();
    }

    //Update weight for news position
    $sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC');
    $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
    $sth->bindValue(':position', $pos_new, PDO::PARAM_STR);
    $sth->execute();

    $weight = 0;
    $stmt_update_n = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight= :weight WHERE bid= :bid');
    while ($_row_bid = $sth->fetch()) {
        ++$weight;
        $stmt_update_n->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update_n->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
        $stmt_update_n->execute();
    }
    $sth->closeCursor();

    if ($weight) {
        $func_id_old = $weight = 0;
        $sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC');
        $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
        $sth->bindValue(':position', $pos_new, PDO::PARAM_STR);
        $sth->execute();

        $stmt_update_n2 = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid AND func_id= :func_id');
        while ($_row_weight = $sth->fetch()) {
            if ($_row_weight['func_id'] == $func_id_old) {
                ++$weight;
            } else {
                $weight = 1;
                $func_id_old = $_row_weight['func_id'];
            }
            $stmt_update_n2->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update_n2->bindValue(':bid', $_row_weight['bid'], PDO::PARAM_INT);
            $stmt_update_n2->bindValue(':func_id', $_row_weight['func_id'], PDO::PARAM_INT);
            $stmt_update_n2->execute();
        }
        $sth->closeCursor();
    }
    $nv_Cache->delMod('themes');

    echo $nv_Lang->getModule('block_update_success');
} else {
    echo 'ERROR';
}
