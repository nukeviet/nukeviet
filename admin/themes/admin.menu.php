<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$restrict_access = (!empty($global_config['restrict_access']) and $global_config['idsite'] > 0);

$submenu['config'] = $nv_Lang->getModule('config');
$allow_func = [
    'main',
    'config',
];

if (!$restrict_access) {
    $submenu['setuplayout'] = $nv_Lang->getModule('setup_layout');
    $submenu['blocks'] = $nv_Lang->getModule('blocks');
    $submenu['xcopyblock'] = $nv_Lang->getModule('xcopyblock');

    $allow_func = array_merge($allow_func, [
        'deletetheme',
        'setuplayout',
        'activatetheme',
        'change_layout',
        'blocks',
        'block_content',
        'block_config',
        'block_outgroup',
        'blocks_change_pos',
        'blocks_change_order',
        'blocks_change_order_group',
        'blocks_change_active',
        'block_change_show',
        'blocks_del',
        'blocks_del_group',
        'blocks_reset_order',
        'sort_order',
        'xcopyblock',
        'loadposition',
        'xcopyprocess',
        'settings'
    ]);
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['package_theme_module'] = $nv_Lang->getModule('package_theme_module');
    $allow_func[] = 'package_theme_module';
    $allow_func[] = 'getfile';
}

if (!$restrict_access) {
    $submenu['settings'] = $nv_Lang->getModule('settings');
}
