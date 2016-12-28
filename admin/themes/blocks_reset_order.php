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

$checkss = $nv_Request->get_string('checkss', 'post');
$theme = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);

if (! empty($theme) and $checkss == md5($theme . NV_CHECK_SESSION)) {

    // load position file
    $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $theme . '/config.ini');
    $position = $xml->xpath('positions');
    $positions = $position[0]->position;
    $array_pos = array();
    for ($j = 0, $count = sizeof($positions); $j < $count; ++$j) {
        $array_pos[] = trim($positions[$j]->tag);
    }

    // Cap nhat block hien thi toan site cho cac function moi phat sinh - Danh cho lap trinh vien
    $array_bid = array();
    // Danh sac tat ca cac block se kiem tra

    $sth = $db->prepare('SELECT bid, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND all_func=1');
    $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
    $sth->execute();

    while (list($bid, $position) = $sth->fetch(3)) {
        if (in_array($position, $array_pos)) {
            $array_bid[$bid] = $position;
        } else {
            // Xóa các block không còn phần cấu hình.
            $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid = ' . $bid);
            $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid = ' . $bid);
        }
    }

    $array_funcid = array();
    // Danh sach ID tat ca cac function co block trong he thong
    $result = $db->query('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC');
    while (list($func_id_i) = $result->fetch(3)) {
        $array_funcid[] = $func_id_i;
    }

    foreach ($array_bid as $bid => $position) {
        $func_list = array();
        // Cac fuction da them block
        $result = $db->query('SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $bid);
        while (list($func_inlist) = $result->fetch(3)) {
            $func_list[] = $func_inlist;
        }

        foreach ($array_funcid as $func_id) {
            if (! in_array($func_id, $func_list)) {
                // Cac function chua duoc them

                $sth = $db->prepare('SELECT MAX(t1.weight)
					FROM ' . NV_BLOCKS_TABLE . '_weight t1
					INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
					WHERE t1.func_id = :func_id AND t2.theme = :theme AND t2.position = :position');
                $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
                $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
                $sth->bindParam(':position', $position, PDO::PARAM_STR);
                $sth->execute();
                $weight = $sth->fetchColumn();

                $weight = intval($weight) + 1;

                $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $bid . ', ' . $func_id . ', ' . $weight . ')');
            }
        }
    }

    // Cap nhat lai weight theo danh sach cac block
    $array_position = array();

    $sth = $db->prepare('SELECT bid, position, weight FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
    $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
    $sth->execute();

    while (list($bid_i, $position, $weight) = $sth->fetch(3)) {
        $array_position[] = $position;
        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i);
    }

    // Kiem tra va cap nhat lai weight tung function
    $array_position = array_unique($array_position);

    foreach ($array_position as $position) {
        $func_id_old = $weight = 0;

        $sth = $db->prepare('SELECT t1.bid, t1.func_id
			FROM ' . NV_BLOCKS_TABLE . '_weight t1
			INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
			WHERE t2.theme= :theme AND t2.position = :position
			ORDER BY t1.func_id ASC, t1.weight ASC');
        $sth->bindParam(':theme', $theme, PDO::PARAM_STR);
        $sth->bindParam(':position', $position, PDO::PARAM_STR);
        $sth->execute();
        while (list($bid_i, $func_id_i) = $sth->fetch(3)) {
            if ($func_id_i == $func_id_old) {
                ++$weight;
            } else {
                $weight = 1;
                $func_id_old = $func_id_i;
            }

            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id_i);
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['block_weight'], 'reset position all block', $admin_info['userid']);
    $nv_Cache->delMod('themes');

    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups');
    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');

    echo $lang_module['block_update_success'];
} else {
    echo 'ERROR';
}