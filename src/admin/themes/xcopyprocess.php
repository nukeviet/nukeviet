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

$theme1 = $nv_Request->get_title('theme1', 'post', '');
$theme2 = $nv_Request->get_title('theme2', 'post', '');

$position = $nv_Request->get_title('position', 'post');
$position = explode(',', $position);
if (md5(NV_CHECK_SESSION . '_' . $module_name . '_xcopyblock_' . $admin_info['userid']) == $nv_Request->get_string('checkss', 'post') and preg_match($global_config['check_theme'], $theme1) and preg_match($global_config['check_theme'], $theme2) and $theme1 != $theme2 and file_exists(NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini') and file_exists(NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini') and !empty($position)) {
    foreach ($position as $pos) {
        $pos = nv_unhtmlspecialchars($pos);
        // Begin drop all exist blocks behavior with theme 2 and position relative
        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid IN (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position)');
        $sth->bindParam(':theme', $theme2, PDO::PARAM_STR);
        $sth->bindParam(':position', $pos, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
        $sth->bindParam(':theme', $theme2, PDO::PARAM_STR);
        $sth->bindParam(':position', $pos, PDO::PARAM_STR);
        $sth->execute();

        // Get and insert block from theme 1
        $sth = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
        $sth->bindParam(':theme', $theme1, PDO::PARAM_STR);
        $sth->bindParam(':position', $pos, PDO::PARAM_STR);
        $sth->execute();
        while ($row = $sth->fetch()) {
            $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (
                theme, module, file_name, title, link, template, heading, position,
                dtime_type, dtime_details, active, bot_visible, groups_view, all_func, weight, config
            ) VALUES (
                :theme, :module, :file_name, :title, :link, :template, :heading, :position,
                :dtime_type, :dtime_details, :active, :bot_visible, :groups_view, :all_func, :weight, :config
            )';

            $data = [];
            $data['theme'] = $theme2;
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
            $data['all_func'] = $row['all_func'];
            $data['weight'] = $row['weight'];
            $data['config'] = $row['config'];
            $bid = $db->insert_id($_sql, 'bid', $data);

            $result_weight = $db->query('SELECT func_id, weight FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid = ' . $row['bid']);
            while ([$func_id, $weight] = $result_weight->fetch(3)) {
                $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $bid . ', ' . $func_id . ', ' . $weight . ')');
            }
        }
    }

    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_groups');
    $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('xcopyblock'), $nv_Lang->getModule('xcopyblock_from') . ' ' . $theme1 . ' ' . $nv_Lang->getModule('xcopyblock_to') . ' ' . $theme2, $admin_info['userid']);
    $nv_Cache->delMod('themes');

    nv_jsonOutput([
        'success' => 1,
        'text' => $nv_Lang->getModule('xcopyblock_success')
    ]);
}

nv_jsonOutput([
    'success' => 0,
    'text' => 'Request not accepted!!!'
]);
