<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:49
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$tables = $nv_Request->get_array( 'tables', 'post', array() );
$type = filter_text_input( 'type', 'post', '' );
$ext = filter_text_input( 'ext', 'post', '' );

if( empty( $tables ) )
{
	$tables = array();
}
elseif( ! is_array( $tables ) )
{
	$tables = array( $tables );
}

$tab_list = array();
$result = $db->sql_query( "SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'" );

while( $item = $db->sql_fetchrow( $result ) )
{
	$tab_list[] = $item[0];
}
$db->sql_freeresult( $result );

$contents = array();
$contents['tables'] = ( empty( $tables ) ) ? $tab_list : array_values( array_intersect( $tab_list, $tables ) );
$contents['type'] = ( $type != "str" ) ? "all" : "str";
$contents['savetype'] = ( $ext != "sql" ) ? "gz" : "sql";

$file_ext = ( $contents['savetype'] == "sql" ) ? "sql" : "sql.gz";
$file_name = md5( $client_info['session_id'] ) . "_backupdata_" . date( "Y-m-d-H-i", time() ) . "." . $file_ext;
$contents['filename'] = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup/" . $file_name;

include ( NV_ROOTDIR . "/includes/core/dump.php" );
$result = nv_dump_save( $contents );

$xtpl = new XTemplate( "save.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( empty( $result ) )
{
	$xtpl->assign( 'ERROR', sprintf( $lang_module['save_error'], NV_LOGS_DIR . "/dump_backup" ) );
	$xtpl->parse( 'main.error' );
}
else
{
	$file = explode( "_", $file_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['savefile'], "File name: " . end( $file ), $admin_info['userid'] );
	
	$xtpl->assign( 'LINK_DOWN', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getfile&amp;filename=" . $file_name . "&amp;checkss=" . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) );
	
	$xtpl->parse( 'main.result' );
}

$page_title = $lang_module['save_data'];

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>