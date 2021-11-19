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
    'add_music',
    'add_singer',
    'list_singer',
    'manage_type'
);
$submenu['add_music'] = $lang_module['add_music'];
$submenu['list_singer'] = $lang_module['list_singer'];
$submenu['add_singer'] = $lang_module['add_singer'];
$submenu['manage_type'] = $lang_module['manage_type'];
define('NV_IS_FILE_ADMIN', true);
