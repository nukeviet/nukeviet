<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'main',
    'googleplus'
);
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'pagetitle';
    $allow_func[] = 'metatags';
    $allow_func[] = 'sitemapPing';
    $allow_func[] = 'robots';
    if (empty($global_config['idsite'])) {
        $allow_func[] = 'rpc';
    }
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_seotools']
);

define('NV_IS_FILE_SEOTOOLS', true);

//Document
$array_url_instruction['pagetitle'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools:pagetitle';
$array_url_instruction['googleplus'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools:googleplus';
$array_url_instruction['sitemapPing'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools:sitemapPing';
$array_url_instruction['metatags'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools';
$array_url_instruction['robots'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools:robots';
$array_url_instruction['rpc'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:seotools:rpc';