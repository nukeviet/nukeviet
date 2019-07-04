<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 07 Mar 2015 03:43:56 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// FIXME xóa plugin sau khi dev xong giao diện admin_nv5
nv_add_hook($module_name, 'get_module_admin_theme', $priority, function($vars) {
    $module_theme = $vars[0];
    $module_name = $vars[1];
    $module_info = $vars[2];
    $op = $vars[3];

    if (defined('NV_ADMIN') and in_array($module_name, [
        'emailtemplates', 'siteinfo', 'settings',
        'database', 'webtools', 'seotools', 'language',
        'modules', 'extensions', 'upload', 'themes', 'authors'
    ]) and !($module_name == 'siteinfo' and $op == 'main' and !isset($_POST['nv_change_theme_config']))) {
        $module_theme = 'admin_nv5';
    }

    return $module_theme;
});
