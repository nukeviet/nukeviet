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

$submenu['setup'] = $lang_module['modules'];
$submenu['vmodule'] = $lang_module['vmodule_add'];

$allow_func = [
    'main',
    'list',
    'setup',
    'vmodule',
    'edit',
    'del',
    'change_weight',
    'change_act',
    'empty_mod',
    'recreate_mod',
    'show',
    'change_func_weight',
    'change_func_submenu',
    'change_alias',
    'change_custom_name',
    'change_site_title',
    'change_block_weight',
    'check_sample_data'
];
