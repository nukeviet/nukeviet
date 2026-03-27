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

$order = $nv_Request->get_int('order', 'post,get');
$bid = $nv_Request->get_int('bid', 'post,get');
$func_id = $nv_Request->get_int('func_id', 'post,get');

$stmt = $db->prepare('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (!empty($row) and $order > 0 and md5($row['theme'] . NV_CHECK_SESSION) == $nv_Request->get_string('checkss', 'post,get')) {
    $theme = $row['theme'];
    $position = $row['position'];

    $weight = 0;
    $sth = $db->prepare('SELECT t1.bid FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.bid!= :bid AND t1.func_id= :func_id AND t2.theme = :theme AND t2.position = :position ORDER BY t1.weight ASC');
    $sth->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
    $sth->bindValue(':func_id', $func_id, PDO::PARAM_INT);
    $sth->bindValue(':theme', $theme, PDO::PARAM_STR);
    $sth->bindValue(':position', $position, PDO::PARAM_STR);
    $sth->execute();

    $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :weight WHERE bid= :bid AND func_id= :func_id');
    while ($_row_bid = $sth->fetch()) {
        ++$weight;
        if ($weight == $order) {
            ++$weight;
        }
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $_row_bid['bid'], PDO::PARAM_INT);
        $stmt_update->bindValue(':func_id', $func_id, PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $sth->closeCursor();

    $stmt_update2 = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight= :order WHERE bid= :bid AND func_id= :func_id');
    $stmt_update2->bindValue(':order', $order, PDO::PARAM_INT);
    $stmt_update2->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
    $stmt_update2->bindValue(':func_id', $func_id, PDO::PARAM_INT);
    $stmt_update2->execute();

    $nv_Cache->delMod('themes');


    echo 'OK';
} else {
    echo 'ERROR';
}
