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

$submenu['main'] = $nv_Lang->getModule('site_config');
if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0)) {
    $submenu['system'] = $nv_Lang->getModule('global_config');
}

if (isset($admin_mods['language'])) {
    $submenu['language'] = $nv_Lang->getGlobal('mod_language');
}

$submenu['smtp'] = $nv_Lang->getModule('smtp_config');
if (defined('NV_IS_GODADMIN')) {
    $submenu['security'] = $nv_Lang->getModule('security');
    $submenu['plugin'] = $nv_Lang->getModule('plugin');
    $submenu['cronjobs'] = $nv_Lang->getGlobal('mod_cronjobs');
    $submenu['ftp'] = $nv_Lang->getModule('ftp_config');
    $submenu['variables'] = $nv_Lang->getModule('variables');
}
