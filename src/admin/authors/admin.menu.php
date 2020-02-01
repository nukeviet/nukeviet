<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$allow_func = ['main', 'edit', '2step'];

if (empty($global_config['spadmin_add_admin']) and $global_config['idsite'] > 0) {
    // Fix add admin for subsite
    $global_config['spadmin_add_admin'] = 1;
}

if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['spadmin_add_admin'] == 1)) {
    $allow_func[] = 'add';
    $allow_func[] = 'suspend';
    $allow_func[] = 'del';
    $allow_func[] = 'users';
    $submenu['add'] = $nv_Lang->getModule('menuadd');
    $submenu['users'] = $nv_Lang->getModule('users');
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['module'] = $nv_Lang->getModule('module_admin');
    $submenu['api-credentials'] = $nv_Lang->getModule('api_cr');
    $submenu['api-roles'] = $nv_Lang->getModule('api_roles');
    $submenu['config'] = $nv_Lang->getModule('config');
    $allow_func[] = 'module';
    $allow_func[] = 'api-credentials';
    $allow_func[] = 'api-roles';
    $allow_func[] = 'config';
}
