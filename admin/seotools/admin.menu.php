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

if (defined('NV_IS_GODADMIN')) {
    $submenu['sitemapPing'] = $lang_module['sitemapPing'];
    $submenu['pagetitle'] = $lang_module['pagetitle'];
    $submenu['metatags'] = $lang_module['metaTagsConfig'];
    $submenu['linktags'] = $lang_module['linkTagsConfig'];
    $submenu['robots'] = $lang_module['robots'];
    if (empty($global_config['idsite'])) {
        $submenu['rpc'] = $lang_module['rpc_setting'];
    }
} elseif (defined('NV_IS_SPADMIN') and $global_config['idsite']) {
    $submenu['metatags'] = $lang_module['metaTagsConfig'];
    $submenu['linktags'] = $lang_module['linkTagsConfig'];
}
