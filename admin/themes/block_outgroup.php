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

$bid = $nv_Request->get_int('bid', 'post');
$func_id = $nv_Request->get_int('func_id', 'post');

$row = $db->query('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();

if ($func_id > 0 and isset($row['bid'])) {
    $sth = $db->prepare('SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme');
    $sth->bindParam(':theme', $row['theme'], PDO::PARAM_STR);
    $sth->execute();
    $maxweight = $sth->fetchColumn();

    $row['weight'] = intval($maxweight) + 1;

    try {
        $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups
			(theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES
			( :theme, :module, :file_name, :title, :link, :template, :position, ' . $row['exp_time'] . ', :active, :groups_view, 0, ' . $row['weight'] . ', :config )';

        $data = array();
        $data['theme'] = $row['theme'];
        $data['module'] = $row['module'];
        $data['file_name'] = $row['file_name'];
        $data['title'] = $row['title'];
        $data['link'] = $row['link'];
        $data['template'] = $row['template'];
        $data['position'] = $row['position'];
        $data['active'] = $row['active'];
        $data['groups_view'] = $row['groups_view'];
        $data['config'] = $row['config'];

        $new_bid = $db->insert_id($_sql, 'bid', $data);

        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET bid=' . $new_bid . ' WHERE bid=' . $bid . ' AND func_id=' . $func_id);

        if (! empty($row['all_func'])) {
            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func=0 WHERE bid=' . $bid);
        }

        $nv_Cache->delMod('themes');

        echo $lang_module['block_front_outgroup_success'] . $new_bid;
    } catch (PDOException $e) {
        echo $lang_module['block_front_outgroup_error_update'];
    }
} else {
    echo $lang_module['block_front_outgroup_cancel'];
}
