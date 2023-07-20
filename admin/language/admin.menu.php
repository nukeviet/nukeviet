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

$submenu['main'] = $nv_Lang->getModule('nv_lang_data');
if (empty($global_config['idsite'])) {
    if ($global_config['lang_multi'] and sizeof($global_config['allow_sitelangs']) > 1) {
        $submenu['countries'] = $nv_Lang->getModule('countries');
    }
    $submenu['interface'] = $nv_Lang->getModule('nv_lang_interface');
    $submenu['check'] = $nv_Lang->getModule('nv_lang_check');
    if (defined('NV_IS_GODADMIN')) {
        $submenu['setting'] = $nv_Lang->getGlobal('mod_settings');
    }
}
