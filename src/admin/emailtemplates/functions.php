<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_EMAILTEMPLATES', true);

$allow_func = [
    'main',
    'categories',
    'contents'
];

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_emailtemplates')
);

$sql = 'SELECT catid, time_add, time_update, weight, is_system, ' . NV_LANG_DATA . '_title title FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories ORDER BY weight ASC';
$global_array_cat = $nv_Cache->db($sql, 'catid', $module_name);
