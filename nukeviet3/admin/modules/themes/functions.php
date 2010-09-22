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
    "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_themes'] 
);
$submenu['setuplayout'] = $lang_module['setup_layout'];
$submenu['blocks'] = $lang_module['blocks'];
$submenu['xcopyblock'] = $lang_module['xcopyblock'];
$submenu['add'] = $lang_module['block_add'];
$allow_func = array( 
    'main', 'blocks', 'setuplayout', 'activatetheme', 'deletetheme', 'change_layout', 'front_add', 'front_del', 'front_outgroup', 'add', 'loadblocks', 'blocks_change_pos', 'blocks_change_pos2', 'blocks_change_func', 'blocks_change_order', 'blocks_del', 'blocks_del_group', 'blocks_list', 'sort_order', 'xcopyblock', 'loadposition', 'xcopyprocess' 
);

if ( defined( "NV_IS_GODADMIN" ) )
{
    $submenu['autoinstall'] = $lang_module['autoinstall'];
    $allow_func[] = 'deletetheme';
    $allow_func[] = 'autoinstall';
    $allow_func[] = 'install_theme';
    $allow_func[] = 'install_check';
    $allow_func[] = 'package_theme';
    $allow_func[] = 'package_theme_module';
    $allow_func[] = "getfile";
}

define( 'NV_IS_FILE_THEMES', true );
?>