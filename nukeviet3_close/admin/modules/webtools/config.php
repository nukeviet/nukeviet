<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );
$submit = $nv_Request->get_string( 'submit', 'post' );

if ( $submit )
{
    $array_config_global = array();
    $array_config_global['autocheckupdate'] = $nv_Request->get_int( 'autocheckupdate', 'post', 0 );
    $array_config_global['autoupdatetime'] = $nv_Request->get_int( 'autoupdatetime', 'post', 24 );
    $array_config_global['update_revision_lang_mode'] = $nv_Request->get_int( 'update_revision_lang_mode', 'post', 1 );
    
	if ( ! in_array( $array_config_global['update_revision_lang_mode'], array( 1, 2, 3, 4 )) )
	{
		$array_config_global['update_revision_lang_mode'] = 1;
	}
	
    foreach ( $array_config_global as $config_name => $config_value )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', '" . mysql_real_escape_string( $config_name ) . "', " . $db->dbescape( $config_value ) . ")" );
    }
    nv_save_file_config_global();
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
    exit();
}

$page_title = $lang_module['config'];
$lang_module['hour'] = $lang_global['hour'];

if ( ! isset ( $global_config['update_revision_lang_mode'] ) or empty ( $global_config['update_revision_lang_mode'] ) )
{
	$global_config['update_revision_lang_mode'] = 1;
}
$update_revision_lang_mode = array();
$update_revision_lang_mode[] = array(
	"key" => 1,  //
	"title" => $lang_module['update_revision_lang_mode_all'],  //
	"selected" => ( 1 == $global_config['update_revision_lang_mode'] ) ? " selected=\"selected\"" : ""  //
);
$update_revision_lang_mode[] = array(
	"key" => 2,  //
	"title" => $lang_module['update_revision_lang_mode_admin'],  //
	"selected" => ( 2 == $global_config['update_revision_lang_mode'] ) ? " selected=\"selected\"" : ""  //
);
$update_revision_lang_mode[] = array(
	"key" => 3,  //
	"title" => $lang_module['update_revision_lang_mode_site'],  //
	"selected" => ( 3 == $global_config['update_revision_lang_mode'] ) ? " selected=\"selected\"" : ""  //
);

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'AUTOCHECKUPDATE', ( $global_config['autocheckupdate'] ) ? ' checked="checked"' : '' );

//
foreach ( $update_revision_lang_mode as $mode )
{
    $xtpl->assign( 'update_revision_lang_mode', $mode );
    $xtpl->parse( 'main.update_revision_lang_mode' );
}

//
for ( $i = 1; $i <= 100; $i ++ )
{
    $xtpl->assign( 'VALUE', $i );
    $xtpl->assign( 'TEXT', $i );
    $xtpl->assign( 'SELECTED', ( $i == $global_config['autoupdatetime'] ? " selected=\"selected\"" : "" ) );
    $xtpl->parse( 'main.updatetime' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>