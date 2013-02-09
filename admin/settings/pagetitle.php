<?php

/**
 * @Project NUKEVIET
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/10/2011, 23:14
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['pagetitle'];

$array_config = array();
if( $nv_Request->isset_request( 'save', 'post' ) )
{
	$array_config['pageTitleMode'] = filter_text_input( 'pageTitleMode', 'post', '', 255 );

	foreach( $array_config as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('sys', 'global', " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")" );
	}
	nv_save_file_config_global();
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

if( ! isset( $global_config['pageTitleMode'] ) or empty( $global_config['pageTitleMode'] ) ) $global_config['pageTitleMode'] = "pagetitle - sitename";

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $global_config );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>