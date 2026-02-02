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
$func_id = $nv_Request->get_int('func_id', 'post');

$row = $db->query('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();

if ($func_id > 0 and isset($row['bid']) and md5(NV_CHECK_SESSION . '_' . $bid) == $nv_Request->get_string('checkss', 'post')) {
    $sth = $db->prepare('SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme');
    $sth->bindParam(':theme', $row['theme'], PDO::PARAM_STR);
    $sth->execute();
    $maxweight = $sth->fetchColumn();

    $row['weight'] = (int) $maxweight + 1;

    try {
        $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (
            theme, module, file_name, title, link, template, heading, position,
            dtime_type, dtime_details, active, bot_visible, groups_view, all_func, weight, config
        ) VALUES (
            :theme, :module, :file_name, :title, :link, :template, :heading, :position,
            :dtime_type, :dtime_details, :active, :bot_visible, :groups_view, 0, ' . $row['weight'] . ', :config
        )';

        $data = [];
        $data['theme'] = $row['theme'];
        $data['module'] = $row['module'];
        $data['file_name'] = $row['file_name'];
        $data['title'] = $row['title'];
        $data['link'] = $row['link'];
        $data['template'] = $row['template'];
        $data['heading'] = $row['heading'];
        $data['position'] = $row['position'];
        $data['dtime_type'] = $row['dtime_type'];
        $data['dtime_details'] = $row['dtime_details'];
        $data['active'] = $row['active'];
        $data['bot_visible'] = $row['bot_visible'];
        $data['groups_view'] = $row['groups_view'];
        $data['config'] = $row['config'];

        $new_bid = $db->insert_id($_sql, 'bid', $data);

        $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET bid=' . $new_bid . ' WHERE bid=' . $bid . ' AND func_id=' . $func_id);

        if (!empty($row['all_func'])) {
            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func=0 WHERE bid=' . $bid);
        }

        $nv_Cache->delMod('themes');

        echo $nv_Lang->getModule('block_front_outgroup_success') . $new_bid;
    } catch (PDOException $e) {
        echo $nv_Lang->getModule('block_front_outgroup_error_update');
    }
} else {
    echo $nv_Lang->getModule('block_front_outgroup_cancel');
}
