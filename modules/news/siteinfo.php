<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

$_arr_siteinfo = array();
$cacheFile = NV_LANG_DATA . '_siteinfo_' . NV_CACHE_PREFIX . '.cache';
if (($cache = $nv_Cache->getItem($mod, $cacheFile)) != false) {
    $_arr_siteinfo = unserialize($cache);
} else {
    // Tong so bai viet
    $_arr_siteinfo['number_publtime'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status= 1')->fetchColumn();

    //So bai viet thanh vien gui toi
    if (!empty($site_mods[$mod]['admins'])) {
        $admins_module = explode(',', $site_mods[$mod]['admins']);
    } else {
        $admins_module = array();
    }
    $result = $db_slave->query('SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE lev=1 OR lev=2');
    while ($row = $result->fetch()) {
        $admins_module[] = $row['admin_id'];
    }
    $_arr_siteinfo['number_users_send'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE admin_id NOT IN (' . implode(',', $admins_module) . ')')->fetchColumn();

    // So bai viet cho dang tu dong
    $_arr_siteinfo['number_pending'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status= 1 AND publtime > ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')')->fetchColumn();

    // So bai viet da het han
    $_arr_siteinfo['number_expired'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE exptime > 0 AND exptime<' . NV_CURRENTTIME)->fetchColumn();

    // So bai viet sap het han
    $_arr_siteinfo['number_exptime'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status = 1 AND exptime>' . NV_CURRENTTIME)->fetchColumn();

    // Tong so binh luan duoc dang
    $_arr_siteinfo['number_comment'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db_slave->quote($mod) . ' AND status = 1')
        ->fetchColumn();

    // Nhac nho cac tu khoa chua co mo ta
    $_arr_siteinfo['number_incomplete'] = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_tags WHERE description = \'\'')->fetchColumn();

    $nv_Cache->setItem($mod, $cacheFile, serialize($_arr_siteinfo));
}

// Tong so bai viet
$siteinfo[] = array(
    'key' => $lang_siteinfo['siteinfo_publtime'],
    'value' => number_format($_arr_siteinfo['number_publtime'])
);

//So bai viet thanh vien gui toi
if ($_arr_siteinfo['number_users_send'] > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_users_send'],
        'value' => number_format($_arr_siteinfo['number_users_send'])
    );
}

// So bai viet cho dang tu dong
if ($_arr_siteinfo['number_pending'] > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_pending'],
        'value' => number_format($_arr_siteinfo['number_pending'])
    );
}

// So bai viet da het han
if ($_arr_siteinfo['number_expired'] > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_expired'],
        'value' => number_format($_arr_siteinfo['number_expired'])
    );
}

// So bai viet sap het han
if ($_arr_siteinfo['number_exptime'] > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_exptime'],
        'value' => number_format($_arr_siteinfo['number_exptime'])
    );
}

// Tong so binh luan duoc dang
if ($_arr_siteinfo['number_comment'] > 0) {
    $siteinfo[] = array(
        'key' => $lang_siteinfo['siteinfo_comment'],
        'value' => number_format($_arr_siteinfo['number_comment'])
    );
}

// Nhac nho cac tu khoa chua co mo ta
if (!empty($module_config[$mod]['tags_remind']) and $_arr_siteinfo['number_incomplete'] > 0) {
    $pendinginfo[] = array(
        'key' => $lang_siteinfo['siteinfo_tags_incomplete'],
        'value' => number_format($_arr_siteinfo['number_incomplete']),
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=tags&amp;incomplete=1'
    );
}