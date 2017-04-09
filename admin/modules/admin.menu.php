<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['setup'] = $lang_module['modules'];
$submenu['vmodule'] = $lang_module['vmodule_add'];

$allow_func = array(
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
);
