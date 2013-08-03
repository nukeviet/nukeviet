<?php

/**
 * @Project NUKEVIET
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/10/2011, 23:14
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['pagetitle'];

$array_config = array();
if( $nv_Request->isset_request( 'save', 'post' ) )
{
	$array_config['pageTitleMode'] = nv_substr( $nv_Request->get_title( 'pageTitleMode', 'post', '', 1), 0, 255 );

	foreach( $array_config as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('sys', 'site', " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")" );
	}
	nv_delete_all_cache( false );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	exit();
}

if( ! isset( $global_config['pageTitleMode'] ) or empty( $global_config['pageTitleMode'] ) ) $global_config['pageTitleMode'] = "pagetitle - sitename";

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $global_config );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $content );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>