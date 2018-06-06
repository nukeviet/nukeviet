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

$submenu['googleplus'] = $nv_Lang->getModule('googleplus');
if (defined('NV_IS_GODADMIN')) {
    $submenu['sitemapPing'] = $nv_Lang->getModule('sitemapPing');
    $submenu['pagetitle'] = $nv_Lang->getModule('pagetitle');
    $submenu['metatags'] = $nv_Lang->getModule('metaTagsConfig');
    $submenu['robots'] = $nv_Lang->getModule('robots');
    if (empty($global_config['idsite'])) {
        $submenu['rpc'] = $nv_Lang->getModule('rpc_setting');
    }
}
