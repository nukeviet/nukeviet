<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 03 Jul 2014 04:35:32 GMT
 */

if (!defined('NV_ADMIN'))
    die('Stop!!!');

global $module_config;

$admin_id = $admin_info['admin_id'];
$NV_IS_ADMIN_MODULE = false;
$NV_IS_ADMIN_FULL_MODULE = false;
if (defined('NV_IS_SPADMIN')) {
    $NV_IS_ADMIN_MODULE = true;
    $NV_IS_ADMIN_FULL_MODULE = true;
} else {
    if (isset($array_cat_admin[$admin_id][0])) {
        $NV_IS_ADMIN_MODULE = true;
        if (intval($array_cat_admin[$admin_id][0]['admin']) == 2) {
            $NV_IS_ADMIN_FULL_MODULE = true;
        }
    }
}

if ($NV_IS_ADMIN_MODULE) {
    $submenu['signer'] = $lang_module['signer'];
    $submenu['scontent'] = $lang_module['scontent_add'];
    $submenu['area'] = $lang_module['area'];
    $submenu['cat'] = $lang_module['cat'];
    $submenu['subject'] = $lang_module['subject'];
}

if ($NV_IS_ADMIN_FULL_MODULE) {
    $submenu['admins'] = $lang_module['admins'];
    $submenu['config'] = $lang_module['config'];
}
if ($module_config[$module_name]['activecomm']) {
    $submenu['examine'] = $lang_module['examine'];
}