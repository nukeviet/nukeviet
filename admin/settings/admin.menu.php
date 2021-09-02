<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$site_fulladmin = (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0));

$submenu['main'] = $lang_module['site_config'];

if ($site_fulladmin) {
    $submenu['system'] = $lang_module['global_config'];
}

if (isset($admin_mods['language'])) {
    $submenu['language'] = $lang_global['mod_language'];
}

$submenu['smtp'] = $lang_module['smtp_config'];

if ($site_fulladmin) {
    $submenu['security'] = $lang_module['security'];
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['plugin'] = $lang_module['plugin'];
    $submenu['cronjobs'] = $lang_global['mod_cronjobs'];
    $submenu['ftp'] = $lang_module['ftp_config'];
    $submenu['variables'] = $lang_module['variables'];
}
