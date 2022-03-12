<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'Videoclips',
    'modfuncs' => 'main,topic,detail',
    'submenu' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.03',
    'date' => 'Tuesday, August 14, 2018 9:09:38 AM GMT+07:00',
    'author' => 'VINADES (contact@vinades.vn)',
    'note' => 'Module playback of video-clips',
    'uploads_dir' => array(
        $module_upload,
        $module_upload . '/icons',
        $module_upload . '/images',
        $module_upload . '/video'
    ),
    'files_dir' => array($module_upload)
);
