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
if (csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_xcopyblock') and preg_match($global_config['check_theme'], $theme1) and preg_match($global_config['check_theme'], $theme2) and $theme1 != $theme2 and file_exists(NV_ROOTDIR . '/themes/' . $theme1 . '/config.ini') and file_exists(NV_ROOTDIR . '/themes/' . $theme2 . '/config.ini') and !empty($position)) {
    foreach ($position as $pos) {
        $pos = nv_unhtmlspecialchars($pos);
        // Begin drop all exist blocks behavior with theme 2 and position relative
        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid IN (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position)');
        $sth->bindValue(':theme', $theme2, PDO::PARAM_STR);
        $sth->bindValue(':position', $pos, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
        $sth->bindValue(':theme', $theme2, PDO::PARAM_STR);
        $sth->bindValue(':position', $pos, PDO::PARAM_STR);
        $sth->execute();

        // Get and insert block from theme 1
        $sth = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
        $sth->bindValue(':theme', $theme1, PDO::PARAM_STR);
        $sth->bindValue(':position', $pos, PDO::PARAM_STR);
        $sth->execute();

        $sth_ins = $db->prepare('INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (
            theme, module, file_name, title, link, template, heading, position,
            dtime_type, dtime_details, active, bot_visible, groups_view, all_func, weight, config
        ) VALUES (
            :theme, :module, :file_name, :title, :link, :template, :heading, :position,
            :dtime_type, :dtime_details, :active, :bot_visible, :groups_view, :all_func, :weight, :config
        )');

        $stmt_weight = $db->prepare('SELECT func_id, weight FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid = :bid');
        $stmt_insert = $db->prepare('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (:new_bid, :func_id, :weight)');

        while ($row = $sth->fetch()) {
            $sth_ins->bindValue(':theme', $theme2, PDO::PARAM_STR);
            $sth_ins->bindValue(':module', $row['module'], PDO::PARAM_STR);
            $sth_ins->bindValue(':file_name', $row['file_name'], PDO::PARAM_STR);
            $sth_ins->bindValue(':title', $row['title'], PDO::PARAM_STR);
            $sth_ins->bindValue(':link', $row['link'], PDO::PARAM_STR);
            $sth_ins->bindValue(':template', $row['template'], PDO::PARAM_STR);
            $sth_ins->bindValue(':heading', $row['heading'], PDO::PARAM_INT);
            $sth_ins->bindValue(':position', $row['position'], PDO::PARAM_STR);
            $sth_ins->bindValue(':dtime_type', $row['dtime_type'], PDO::PARAM_INT);
            $sth_ins->bindValue(':dtime_details', $row['dtime_details'], PDO::PARAM_STR);
            $sth_ins->bindValue(':active', $row['active'], PDO::PARAM_INT);
            $sth_ins->bindValue(':bot_visible', $row['bot_visible'], PDO::PARAM_INT);
            $sth_ins->bindValue(':groups_view', $row['groups_view'], PDO::PARAM_STR);
            $sth_ins->bindValue(':all_func', $row['all_func'], PDO::PARAM_INT);
            $sth_ins->bindValue(':weight', $row['weight'], PDO::PARAM_INT);
            $sth_ins->bindValue(':config', $row['config'], PDO::PARAM_STR);
            $sth_ins->execute();
            $bid = $db->lastInsertId();

            $stmt_weight->bindValue(':bid', $row['bid'], PDO::PARAM_INT);
            $stmt_weight->execute();

            while ($_row_weight = $stmt_weight->fetch()) {
                $stmt_insert->bindValue(':new_bid', $bid, PDO::PARAM_INT);
                $stmt_insert->bindValue(':func_id', $_row_weight['func_id'], PDO::PARAM_INT);
                $stmt_insert->bindValue(':weight', $_row_weight['weight'], PDO::PARAM_INT);
                $stmt_insert->execute();
            }
            $stmt_weight->closeCursor();
        }
        $sth->closeCursor();
    }

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
