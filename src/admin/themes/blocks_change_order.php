<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$order = $nv_Request->get_int('order', 'post,get');
$bid = $nv_Request->get_int('bid', 'post,get');
$func_id = $nv_Request->get_int('func_id', 'post,get');

list($bid, $theme, $position) = $db->query('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch(3);

if ($order > 0 and $bid > 0 and md5($theme . NV_CHECK_SESSION) == $nv_Request->get_string('checkss', 'post,get')) {
    $weight = 0;
    $sth = $db->prepare('SELECT t1.bid FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.bid!=' . $bid . ' AND t1.func_id=' . $func_id . ' AND t2.theme = :theme AND t2.position = :position ORDER BY t1.weight ASC');
    $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
    $sth->bindParam(':position', $position, PDO::PARAM_STR);
    $sth->execute();
    while (list($bid_i) = $sth->fetch(3)) {
        ++$weight;
        if ($weight == $order) {
            ++$weight;
        }
        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id);
    }

    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $order . ' WHERE bid=' . $bid . ' AND func_id=' . $func_id);

    $nv_Cache->delMod('themes');

    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');

    echo 'OK';
} else {
    echo 'ERROR';
}
