<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 20/1/2011, 2:13
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "adminpage.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$adminThemes = array( '' );
$adminThemes = array_merge( $adminThemes, nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme_admin'] ) );
unset( $adminThemes[0] );

$loginModes = array( '1' => $lang_module['loginMode1'], '2' => $lang_module['loginMode2'], '3' => $lang_module['loginMode3'] );

if ( $nv_Request->isset_request( 'save', 'get' ) and ( $nv_Request->get_int( 'save', 'get' ) == 1 ) )
{
    $admin_theme = $nv_Request->get_int( 'admin_theme', 'get' );
    if ( $admin_theme and isset( $adminThemes[$admin_theme] ) )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'admin_theme', " . $db->dbescape( $adminThemes[$admin_theme] ) . ")" );
    }

    $admin_login_mode = $nv_Request->get_int( 'admin_login_mode', 'get' );
    if ( $admin_login_mode and isset( $loginModes[$admin_login_mode] ) )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'admin_login_mode', " . $db->dbescape( $admin_login_mode ) . ")" );
    }

    nv_save_file_config_global();

    $xtpl->assign( 'LOAD_SETTING2', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=settings&" . NV_OP_VARIABLE . "=adminpage&setting=1" );
    $xtpl->parse( 'save' );
    echo $xtpl->text( 'save' );
    exit;
}

if ( $nv_Request->isset_request( 'setting', 'get' ) and ( $nv_Request->get_int( 'setting', 'get' ) == 1 ) )
{
    $admin_theme = ( isset( $global_config['admin_theme'] ) and ! empty( $global_config['admin_theme'] ) and in_array( $global_config['admin_theme'], $adminThemes ) ) ? $global_config['admin_theme'] : "admin_default";
    $admin_login_mode = ( isset( $global_config['admin_login_mode'] ) and isset( $loginModes[$global_config['admin_login_mode']] ) ) ? $global_config['admin_login_mode'] : '3';

    foreach ( $adminThemes as $value => $name )
    {
        $xtpl->assign( 'THEME_VALUE', $value );
        $xtpl->assign( 'THEME_NAME', $name );
        $xtpl->assign( 'THEME_SELECTED', ( $name == $admin_theme ? " selected=\"selected\"" : "" ) );
        $xtpl->parse( 'setting.admin_theme' );
    }

    foreach ( $loginModes as $value => $name )
    {
        $xtpl->assign( 'MODE_VALUE', $value );
        $xtpl->assign( 'MODE_NAME', $name );
        $xtpl->assign( 'MODE_SELECTED', ( $value == $admin_login_mode ? " selected=\"selected\"" : "" ) );
        $xtpl->parse( 'setting.admin_login_mode' );
    }

    $xtpl->assign( 'LOAD_SAVE', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=settings&" . NV_OP_VARIABLE . "=adminpage&save=1" );

    $xtpl->parse( 'setting' );
    echo $xtpl->text( 'setting' );
    exit;
}

$xtpl->assign( 'LOAD_SETTING', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=settings&" . NV_OP_VARIABLE . "=adminpage&setting=1" );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['adminpage_settings'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>