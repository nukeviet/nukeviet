<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'main',
    'content',
    'alias',
    'change_status',
    'change_weight',
    'del',
    'view'
);

define('NV_IS_FILE_ADMIN', true);

if (defined('NV_IS_SPADMIN')) {
	$allow_func[] = 'config';
}

// Document
$array_url_instruction['content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:about#them_bai_mới';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:about#cấu_hinh';
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:about';

// Get Config Module
$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
$page_config = array();
foreach ($list as $values) {
    $page_config[$values['config_name']] = $values['config_value'];
}