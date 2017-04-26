<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Two-Step Verification',
    'modfuncs' => 'main,setup,confirm',
    'submenu' => 'main,setup,confirm',
    'is_sysmod' => 1,
    'virtual' => 0,
    'version' => '4.1.02',
    'date' => 'Sun, 23 Apr 2017 16:59:59 GMT',
    'author' => 'VINADES (contact@vinades.vn)',
    'note' => 'Two-Step Verification'
);