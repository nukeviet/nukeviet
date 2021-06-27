<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['config'] = $nv_Lang->getModule('config');
$submenu['setuplayout'] = $nv_Lang->getModule('setup_layout');
$submenu['blocks'] = $nv_Lang->getModule('blocks');
$submenu['xcopyblock'] = $nv_Lang->getModule('xcopyblock');

$allow_func = [
    'main',
    'deletetheme',
    'setuplayout',
    'activatetheme',
    'change_layout',
    'config',
    'blocks',
    'block_content',
    'block_config',
    'block_outgroup',
    'loadblocks',
    'blocks_change_pos',
    'blocks_change_order',
    'blocks_change_order_group',
    'blocks_change_active',
    'block_change_show',
    'blocks_del',
    'blocks_del_group',
    'blocks_func',
    'blocks_reset_order',
    'sort_order',
    'xcopyblock',
    'loadposition',
    'xcopyprocess',
    'settings'
];

if (defined('NV_IS_GODADMIN')) {
    $submenu['package_theme_module'] = $nv_Lang->getModule('package_theme_module');
    $allow_func[] = 'package_theme_module';
    $allow_func[] = 'getfile';
}

$submenu['settings'] = $nv_Lang->getModule('settings');
