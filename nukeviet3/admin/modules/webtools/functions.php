<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_WEBTOOLS', true );

$menu_top = array( 
    "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_webtools'] 
);

$submenu['clearsystem'] = $lang_module['clearsystem'];
$submenu['sitemapPing'] = $lang_module['sitemapPing'];
$submenu['checkupdate'] = $lang_module['checkupdate'];
$submenu['revision'] = $lang_module['revision'];

$allow_func = array( 
    'main', 'clearsystem', 'sitemapPing', 'checkupdate', 'revision' 
);

?>