<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['main'] = $nv_Lang->getModule('site_config');
if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0)) {
    $submenu['system'] = $nv_Lang->getModule('global_config');
}

$submenu['smtp'] = $nv_Lang->getModule('smtp_config');
if (defined('NV_IS_GODADMIN')) {
    $submenu['security'] = $nv_Lang->getModule('security');
    $submenu['plugin'] = $nv_Lang->getModule('plugin');
    $submenu['cronjobs'] = $nv_Lang->getGlobal('mod_cronjobs');
    $submenu['ftp'] = $nv_Lang->getModule('ftp_config');
    $submenu['variables'] = $nv_Lang->getModule('variables');
}
