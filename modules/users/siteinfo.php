<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

// So thanh vien
$number = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data)->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_user'], 'value' => number_format($number) );
}

// So thanh vien doi kich hoat
$number = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_reg')->fetchColumn();
if ($number > 0) {
    $pendinginfo[] = array(
        'key' => $lang_siteinfo['siteinfo_waiting'],
        'value' => number_format($number),
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=user_waiting'
    );
}

// So thanh vien dang ky vao nhom
$level = $admin_info['level'];

$access_admin = $db->query("SELECT content FROM " . $db_config['prefix'] . "_" . $mod_data . "_config WHERE config='access_admin'")->fetchColumn();
$access_admin = unserialize($access_admin);

if (isset($access_admin['access_groups'][$level]) and $access_admin['access_groups'][$level] == 1) {
    $pending_lists = $group_ids = array();
    
    $sql = 'SELECT COUNT(*) num_users, group_id FROM ' . $db_config['prefix'] . '_' . $mod_data . '_groups_users WHERE approved = 0 GROUP BY group_id';
    $result = $db->query($sql);
    
    while ($row = $result->fetch()) {
        $row['title'] = 'N/A';
        $pending_lists[$row['group_id']] = $row;
        $group_ids[$row['group_id']] = $row['group_id'];
    }

    if (!empty($group_ids)) {
        $sql = 'SELECT group_id, title FROM ' . $db_config['prefix'] . '_' . $mod_data . '_groups WHERE group_id > 9 AND group_id IN(' . implode(',', $group_ids) . ')';
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $pending_lists[$row['group_id']]['title'] = $row['title'];
        }
    }
    
    if (!empty($pending_lists)) {
        foreach ($pending_lists as $row) {
            $pendinginfo[] = array(
                'key' => sprintf($lang_siteinfo['group_user_peding'], $row['title']),
                'value' => number_format($row['num_users'], 0, ',', '.'),
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=groups&userlist=' . $row['group_id']
            );
        }
    }
}