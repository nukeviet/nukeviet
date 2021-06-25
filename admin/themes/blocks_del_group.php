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

$list = $nv_Request->get_string('list', 'post,get');
$selectthemes = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);

$array_bid = explode(',', $list);
$array_bid = array_map('intval', $array_bid);

if (!empty($array_bid) and md5($selectthemes . NV_CHECK_SESSION) == $nv_Request->get_string('checkss', 'post,get')) {
    $array_expression = [];
    $result = $db->query('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid in (' . implode(',', $array_bid) . ')');

    while (list($bid_i, $theme_i, $position_i) = $result->fetch(3)) {
        $array_expression[$theme_i][$position_i][] = $bid_i;
    }

    if (!empty($array_expression)) {
        foreach ($array_expression as $theme_i => $array_data_i) {
            foreach ($array_data_i as $position => $array_position) {
                $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid in (' . implode(',', $array_position) . ')');
                $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (' . implode(',', $array_position) . ')');

                $weight = 0;
                $sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC');
                $sth->bindParam(':theme', $theme_i, PDO::PARAM_STR);
                $sth->bindParam(':position', $position, PDO::PARAM_STR);
                $sth->execute();
                while (list($bid_i) = $sth->fetch(3)) {
                    ++$weight;
                    $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET weight=' . $weight . ' WHERE bid=' . $bid_i);
                }

                $func_id_old = $weight = 0;
                $sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1
				INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
				WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC');
                $sth->bindParam(':theme', $theme_i, PDO::PARAM_STR);
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
        }

        $nv_Cache->delMod('themes');

        $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');
        $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups');
    }
}

echo $lang_module['block_delete_success'];
