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

$order = $nv_Request->get_int('order', 'post,get');
$bid = $nv_Request->get_int('bid', 'post,get');

list($bid, $theme, $position) = $db->query('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch(3);

if ($order > 0 and $bid > 0) {
    $weight = 0;
    $sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid!=' . $bid . ' AND theme= :theme AND position= :position ORDER BY weight ASC');
    $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
    $sth->bindParam(':position', $position, PDO::PARAM_STR);
    $sth->execute();
    while (list($bid_i) = $sth->fetch(3)) {
        ++$weight;
        if ($weight == $order) {
            ++$weight;
        }
        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight=' . $weight . ' WHERE bid=' . $bid_i);
    }

    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight=' . $order . ' WHERE bid=' . $bid);
    $nv_Cache->delMod('themes');

    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups');
    echo 'OK';
} else {
    echo 'ERROR';
}
