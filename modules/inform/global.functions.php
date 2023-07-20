<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

function groups_list($mod_data = 'users')
{
    global $nv_Cache, $db, $db_config, $global_config;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;

    $query = 'SELECT g.group_id, d.title, g.group_type, g.exp_time FROM ' . $_mod_table . '_groups AS g LEFT JOIN ' . $_mod_table . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE g.act=1 AND (g.idsite = " . $global_config['idsite'] . ' OR (g.idsite =0 AND g.siteus = 1)) ORDER BY g.idsite, g.weight';
    $list = $nv_Cache->db($query, '', $mod_data);

    if (empty($list)) {
        return [];
    }

    $groups = [];
    for ($i = 0, $count = sizeof($list); $i < $count; ++$i) {
        if (!($list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME) and $list[$i]['group_type']) {
            $groups[$list[$i]['group_id']] = $list[$i]['title'];
        }
    }

    return $groups;
}

function admins_list()
{
    global $global_config, $nv_Cache;

    $sql = 'SELECT t1.admin_id, t2.first_name, t2.last_name FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid ORDER BY t1.lev ASC';
    $list = $nv_Cache->db($sql, '', 'authors');

    $adminlist = [];
    if (!empty($list)) {
        foreach ($list as $row) {
            $full_name = $global_config['name_show'] ? [$row['first_name'], $row['last_name']] : [$row['last_name'], $row['first_name']];
            $full_name = array_filter($full_name);
            $adminlist[$row['admin_id']] = implode(' ', array_map('trim', $full_name));
        }
    }

    return $adminlist;
}

function userlist_by_ids($ids, $grid = 0, $full = false)
{
    global $db, $global_config;

    if (is_array($ids)) {
        $ids = implode(',', $ids);
    }

    if ($grid) {
        $sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $ids . ') AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id = ' . $grid . ')';
    } else {
        $sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . $ids . ')';
    }
    $result = $db->query($sql);
    $users = [];
    while ($row = $result->fetch()) {
        $full_name = $global_config['name_show'] ? [$row['first_name'], $row['last_name']] : [$row['last_name'], $row['first_name']];
        $full_name = array_filter($full_name);
        $full_name = implode(' ', array_map('trim', $full_name));

        if ($full) {
            $users[$row['userid']] = [$row['userid'], $row['username'], $full_name];
        } else {
            $users[$row['userid']] = $full_name;
        }
    }

    return $users;
}
