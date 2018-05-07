<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:24:58 AM
 */

// Reload lại menu khi module bị xóa
$callback = function($vars, $module_name, $module_info) {
    global $db, $nv_Cache, $admin_info;

    $del_modname = $vars[0];
    //$del_admin_info = $vars[1];
    $module_data = $module_info['module_data'];
    $module_file = $module_info['module_file'];

    $sql = 'SELECT id, parentid, mid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE module_name=' . $db->quote($del_modname);
    $lists = $db->query($sql)->fetchAll();

    require NV_ROOTDIR . '/modules/' . $module_file . '/admin.class.php';
    $nv_menu = new nv_menu($module_data, $module_name, $admin_info);

    foreach ($lists as $row) {
        $nv_menu->delRow($row['id'], $row['parentid']);
        $nv_menu->fixMenuOrder($row['mid']);
    }
    $nv_Cache->delMod($module_name);
};
nv_add_hook('after_module_deleted', $callback, $priority, $module_name);
