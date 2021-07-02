<?php

/**
 * @Project NUKEVIET 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
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
    $submenu['add'] = $lang_module['menuadd'];
    $submenu['users'] = $lang_module['users'];
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['module'] = $lang_module['module_admin'];
    $submenu['api-credentials'] = $lang_module['api_cr'];
    $submenu['api-roles'] = $lang_module['api_roles'];
    $submenu['config'] = $lang_module['config'];
    $allow_func[] = 'module';
    $allow_func[] = 'api-credentials';
    $allow_func[] = 'api-roles';
    $allow_func[] = 'config';
}
