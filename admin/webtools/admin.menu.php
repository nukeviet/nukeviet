<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['statistics'] = $lang_module['global_statistics'];
$submenu['clearsystem'] = $lang_module['clearsystem'];
if (empty($global_config['idsite'])) {
    $submenu['checkupdate'] = $lang_module['checkupdate'];
    $submenu['config'] = $lang_module['config'];
}
