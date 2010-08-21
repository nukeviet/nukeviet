<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 1:58
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

unset( $page_title, $select_options );
$select_options = array();

$menu_top = array( 
    "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_settings'] 
);
$submenu['main'] = $lang_module['lang_site_config'];
$submenu['system'] = $lang_module['global_config'];
$submenu['smtp'] = $lang_module['smtp_config'];
$submenu['ftp'] = $lang_module['ftp_config'];
$submenu['bots'] = $lang_module['bots_config'];
$submenu['banip'] = $lang_module['banip'];
if ( defined( 'NV_IS_GODADMIN' ) )
{
    $submenu['uploadconfig'] = $lang_module['uploadconfig'];
    $allow_func = array( 
        'main', 'system', 'bots', 'checkupdate', 'smtp', 'ftp', 'banip', 'uploadconfig' 
    );
}
else
{
    $allow_func = array( 
        'main', 'system', 'bots', 'checkupdate', 'smtp', 'ftp', 'banip'
    );
}
$submenu['checkupdate'] = $lang_module['checkupdate'];

define( 'NV_IS_FILE_SETTINGS', true );
?>