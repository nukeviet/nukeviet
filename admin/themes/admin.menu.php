<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['config'] = $lang_module['config'];
$submenu['setuplayout'] = $lang_module['setup_layout'];
$submenu['blocks'] = $lang_module['blocks'];
$submenu['xcopyblock'] = $lang_module['xcopyblock'];

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
    $submenu['package_theme_module'] = $lang_module['package_theme_module'];
    $allow_func[] = 'package_theme_module';
    $allow_func[] = 'getfile';
}

$submenu['settings'] = $lang_module['settings'];
