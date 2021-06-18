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
    $submenu['department'] = $lang_module['department_title'];
    $submenu['supporter'] = $lang_module['supporter'];
}
$submenu['send'] = $lang_module['send_title'];
if (defined('NV_IS_SPADMIN')) {
    $submenu['config'] = $lang_module['config'];
}
