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

$submenu['main'] = $lang_module['nv_lang_data'];
if (empty($global_config['idsite'])) {
    if ($global_config['lang_multi'] and sizeof($global_config['allow_sitelangs']) > 1) {
        $submenu['countries'] = $lang_module['countries'];
    }
    $submenu['interface'] = $lang_module['nv_lang_interface'];
    $submenu['check'] = $lang_module['nv_lang_check'];
    if (defined('NV_IS_GODADMIN')) {
        $submenu['setting'] = $lang_global['mod_settings'];
    }
}
