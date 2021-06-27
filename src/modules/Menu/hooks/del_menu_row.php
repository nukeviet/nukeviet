<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Reload lại menu khi module bị xóa
$callback = function ($vars, $from_data, $receive_data) {
    global $db, $nv_Cache, $admin_info;

    $del_modname = $vars[0];
    //$del_admin_info = $vars[1];
    $module_name = $receive_data['module_name'];
    $module_data = $receive_data['module_info']['module_data'];
    $module_file = $receive_data['module_info']['module_file'];

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
nv_add_hook($module_name, 'after_module_deleted', $priority, $callback, $hook_module);
