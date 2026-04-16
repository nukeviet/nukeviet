<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_SPADMIN')) {
    header('location: ' . NV_BASE_SITEURL . 'admin/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    exit();
}

define('NV_IS_FILE_ADMIN', true);

$allow_func = ['main', 'schemas', 'schemas-save', 'schemas-columns', 'schemas-mvc', 'config-step1', 'config-step2'];
