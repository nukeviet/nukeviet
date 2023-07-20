<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$site_fulladmin = (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0));

$submenu['main'] = $nv_Lang->getModule('site_config');

if ($site_fulladmin) {
    $submenu['system'] = $nv_Lang->getModule('global_config');
}

if (isset($admin_mods['language'])) {
    $submenu['language'] = $nv_Lang->getGlobal('mod_language');
}

$submenu['smtp'] = $nv_Lang->getModule('smtp_config');

if ($site_fulladmin) {
    $submenu['security'] = $nv_Lang->getModule('security');
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['plugin'] = $nv_Lang->getModule('plugin');
    $submenu['cronjobs'] = $nv_Lang->getGlobal('mod_cronjobs');
    $submenu['ftp'] = $nv_Lang->getModule('ftp_config');
    $submenu['cdn_backendhost'] = $nv_Lang->getModule('cdn_backendhost');
    $submenu['ssettings'] = $nv_Lang->getModule('ssettings');
    $submenu['variables'] = $nv_Lang->getModule('variables');
    $submenu['custom'] = $nv_Lang->getModule('custom');
}
