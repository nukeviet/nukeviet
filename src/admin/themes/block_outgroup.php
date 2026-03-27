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

$stmt = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid= :bid');
$stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if ($func_id > 0 and isset($row['bid']) and md5(NV_CHECK_SESSION . '_' . $bid) == $nv_Request->get_string('checkss', 'post')) {
    $sth = $db->prepare('SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme');
    $sth->bindValue(':theme', $row['theme'], PDO::PARAM_STR);
    $sth->execute();
    $maxweight = $sth->fetchColumn();

    $row['weight'] = (int) $maxweight + 1;

    try {
        $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (
            theme, module, file_name, title, link, template, heading, position,
            dtime_type, dtime_details, active, bot_visible, groups_view, all_func, weight, config
        ) VALUES (
            :theme, :module, :file_name, :title, :link, :template, :heading, :position,
            :dtime_type, :dtime_details, :active, :bot_visible, :groups_view, 0, :weight, :config
        )';

        $sth_ins = $db->prepare($_sql);
        $sth_ins->bindValue(':theme', $row['theme'], PDO::PARAM_STR);
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
        $sth_ins->bindValue(':weight', $row['weight'], PDO::PARAM_INT);
        $sth_ins->bindValue(':config', $row['config'], PDO::PARAM_STR);
        $sth_ins->execute();
        $new_bid = $db->lastInsertId();

        $stmt_update = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET bid= :new_bid WHERE bid= :bid AND func_id= :func_id');
        $stmt_update->bindValue(':new_bid', $new_bid, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt_update->bindValue(':func_id', $func_id, PDO::PARAM_INT);
        $stmt_update->execute();

        if (!empty($row['all_func'])) {
            $stmt_all_func = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func=0 WHERE bid= :bid');
            $stmt_all_func->bindValue(':bid', $bid, PDO::PARAM_INT);
            $stmt_all_func->execute();
        }

        $nv_Cache->delMod('themes');

        echo $nv_Lang->getModule('block_front_outgroup_success') . $new_bid;
    } catch (Throwable $e) {
        trigger_error($e);
        echo $nv_Lang->getModule('block_front_outgroup_error_update');
    }
} else {
    echo $nv_Lang->getModule('block_front_outgroup_cancel');
}
