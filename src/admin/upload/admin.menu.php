<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

if (defined('NV_IS_SPADMIN')) {
    $submenu['thumbconfig'] = $nv_Lang->getModule('thumbconfig');
    $submenu['config'] = $nv_Lang->getModule('configlogo');
    if (defined('NV_IS_GODADMIN')) {
        $submenu['uploadconfig'] = $nv_Lang->getModule('uploadconfig');
    }
}
