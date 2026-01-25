<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['statistics'] = $nv_Lang->getModule('global_statistics');
$submenu['clearsystem'] = $nv_Lang->getModule('clearsystem');
if (defined('NV_IS_SPADMIN')) {
    $submenu['errorlog'] = $nv_Lang->getModule('errorlog');
}
if (empty($global_config['idsite'])) {
    $submenu['checkupdate'] = $nv_Lang->getModule('checkupdate');
    $submenu['config'] = $nv_Lang->getModule('config');
}
