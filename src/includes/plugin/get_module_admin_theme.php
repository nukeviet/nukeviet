<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// FIXME xóa plugin sau khi dev xong giao diện admin_nv5
nv_add_hook($module_name, 'get_module_admin_theme', $priority, function ($vars) {
    $module_theme = $vars[0];
    $module_name = $vars[1];
    $module_info = $vars[2];
    $op = $vars[3];

    if (defined('NV_ADMIN') and in_array($module_name, [
        'emailtemplates', 'siteinfo', 'settings',
        'database', 'webtools', 'seotools', 'language',
        'modules', 'extensions', 'upload', 'themes', 'authors'
    ], true) and !($module_name == 'siteinfo' and $op == 'main' and !isset($_POST['nv_change_theme_config']))) {
        $module_theme = 'admin_nv5';
    }

    return $module_theme;
});
