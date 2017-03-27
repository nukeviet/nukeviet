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

$submenu['googleplus'] = $lang_module['googleplus'];
if (defined('NV_IS_GODADMIN')) {
    $submenu['sitemapPing'] = $lang_module['sitemapPing'];
    $submenu['pagetitle'] = $lang_module['pagetitle'];
    $submenu['metatags'] = $lang_module['metaTagsConfig'];
    $submenu['robots'] = $lang_module['robots'];
    if (empty($global_config['idsite'])) {
        $submenu['rpc'] = $lang_module['rpc_setting'];
    }
}
