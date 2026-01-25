<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_SYS_LOAD')) {
    exit('Stop!!!');
}

$type = $nv_Request->get_string('xsl', 'get') == 'rss' ? 'rss' : 'atom';

$contents = '';
$theme = '';
if ($nv_Request->isset_request('theme', 'get')) {
    $theme = preg_replace('/[^a-zA-Z0-9\_\-]/', '', $nv_Request->get_string('theme', 'get'));
    if (!empty($theme) and file_exists(NV_ROOTDIR . '/themes/' . $theme . '/css/' . $type . '.xsl')) {
        $contents = file_get_contents(NV_ROOTDIR . '/themes/' . $theme . '/css/' . $type . '.xsl');
    }
}

if (empty($contents)) {
    $contents = file_get_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $type . '.xsl');
}

$contents = preg_replace('/\{NV\_BASE\_SITEURL\}/', NV_BASE_SITEURL, $contents);
$contents = preg_replace('/\{NV\_ASSETS\_DIR\}/', NV_ASSETS_DIR, $contents);
$contents = preg_replace('/\{THEME\}/', $theme, $contents);
$contents = preg_replace('/\{MORE\_LANG\}/', $nv_Lang->getGlobal('detail'), $contents);
$lastModified = NV_CURRENTTIME;
nv_xmlOutput($contents, $lastModified);
