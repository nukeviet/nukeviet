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

if (defined('NV_IS_GODADMIN')) {
    $submenu['sitemapPing'] = $nv_Lang->getModule('sitemapPing');
    $submenu['pagetitle'] = $nv_Lang->getModule('pagetitle');
    $submenu['metatags'] = $nv_Lang->getModule('metaTagsConfig');
    $submenu['robots'] = $nv_Lang->getModule('robots');
    if (empty($global_config['idsite'])) {
        $submenu['rpc'] = $nv_Lang->getModule('rpc_setting');
    }
}
