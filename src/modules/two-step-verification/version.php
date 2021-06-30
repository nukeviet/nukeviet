<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Two-Step Verification',
    'modfuncs' => 'main,setup,confirm',
    'submenu' => 'main,setup,confirm',
    'is_sysmod' => 1,
    'virtual' => 0,
    'version' => '5.0.00',
    'date' => 'Tuesday, June 22, 2021 16:00:00 GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => 'Two-Step Verification'
];
