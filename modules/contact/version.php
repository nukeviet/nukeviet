<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Contact',
    'modfuncs' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.03',
    'date' => 'Monday, August 6, 2018 5:00:00 PM GMT+07:00',
    'author' => 'VINADES <contact@vinades.vn>',
    'note' => '',
    'uploads_dir' => array(
        $module_upload
    )
);