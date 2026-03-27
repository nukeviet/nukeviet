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

$list = $nv_Request->get_string('list', 'post,get');
$selectthemes = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);

$array_bid = explode(',', $list);
$array_bid = array_map('intval', $array_bid);

if (!empty($array_bid) and md5($selectthemes . NV_CHECK_SESSION) == $nv_Request->get_string('checkss', 'post,get')) {
    $placeholders = implode(',', array_fill(0, count($array_bid), '?'));
    $result = $db->prepare('SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid in (' . $placeholders . ')');
    foreach ($array_bid as $k => $id) {
        $result->bindValue($k + 1, $id, PDO::PARAM_INT);
    }
    $result->execute();

    while ($_row = $result->fetch()) {
        $array_expression[$_row['theme']][$_row['position']][] = $_row['bid'];
    }
    $result->closeCursor();

    if (!empty($array_expression)) {
        foreach ($array_expression as $theme_i => $array_data_i) {
            foreach ($array_data_i as $position => $array_position) {
                $placeholders2 = implode(',', array_fill(0, count($array_position), '?'));
                $stmt_del_g = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid in (' . $placeholders2 . ')');
                $stmt_del_w = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (' . $placeholders2 . ')');
                $k = 1;
                foreach ($array_position as $id) {
                    $stmt_del_g->bindValue($k, $id, PDO::PARAM_INT);
                    $stmt_del_w->bindValue($k, $id, PDO::PARAM_INT);
                    $k++;
                }
                $stmt_del_g->execute();
                $stmt_del_w->execute();

                $weight = 0;
                $sth = $db->prepare('SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=:theme AND position=:position ORDER BY weight ASC');
                $sth->bindValue(':theme', $theme_i, PDO::PARAM_STR);
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
                $sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1
				INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid
				WHERE t2.theme=:theme AND t2.position=:position ORDER BY t1.func_id ASC, t1.weight ASC');
                $sth->bindValue(':theme', $theme_i, PDO::PARAM_STR);
                $sth->bindValue(':position', $position, PDO::PARAM_STR);
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
        }

        $nv_Cache->delMod('themes');
    }
}

echo $nv_Lang->getModule('block_delete_success');
