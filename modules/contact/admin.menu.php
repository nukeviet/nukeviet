<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

if (defined('NV_IS_SPADMIN')) {
    $submenu['department'] = $nv_Lang->getModule('department_title');
    $submenu['supporter'] = $nv_Lang->getModule('supporter');
}
$submenu['send'] = $nv_Lang->getModule('send_title');
if (defined('NV_IS_SPADMIN')) {
    $submenu['config'] = $nv_Lang->getModule('config');
}
