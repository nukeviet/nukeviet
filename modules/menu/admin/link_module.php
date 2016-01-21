<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

// Lay chu de cua module duoc chon

$sp = '&nbsp;&nbsp;&nbsp;';

$mod_name = $nv_Request->get_title('module', 'post', '');

$stmt = $db->prepare('SELECT title, module_file, module_data FROM ' . NV_MODULES_TABLE . ' WHERE title= :module');
$stmt->bindParam(':module', $mod_name, PDO::PARAM_STR);
$stmt->execute();

list($mod_name, $mod_file, $mod_data) = $stmt->fetch(3);

if (empty($mod_name)) {
    die($lang_module['add_error_module']);
}

$array_item = array();
if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php')) {
    include NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php';
}

// Lấy menu từ các chức năng của module
$funcs_item = $site_mods[$mod_name]['funcs'];
foreach ($funcs_item as $key => $sub_item) {
    if ($sub_item['in_submenu'] == 1) {
        $array_item[] = array(
            'key' => $key,
            'title' => $sub_item['func_custom_name'],
            'alias' => $key
        );
    }
}

if (!empty($array_item)) {
    $xtpl = new XTemplate('rows.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_item as $key => $item1) {
        $parentid = (isset($item1['parentid'])) ? $item1['parentid'] : 0;
        if (empty($parentid)) {
            $item1['module'] = $mod_name;

            $xtpl->assign('item', $item1);
            $xtpl->parse('main.link.item');

            $array_submenu = array();
            nv_menu_get_submenu($key, '', $array_item, $sp);
            foreach ($array_submenu as $item2) {
                $xtpl->assign('item', $item2);
                $xtpl->parse('main.link.item');
            }
        }
    }

    $xtpl->parse('main.link');
    $contents = $xtpl->text('main.link');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}
die('&nbsp;');
