<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:51
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

if( $nv_Request->get_bool( 'show_tabs', 'post' ) )
{
	nv_show_tables();
	exit;
}

if( $nv_Request->isset_request( 'tab', 'get' ) and preg_match( "/^(" . $db_config['prefix'] . ")\_[a-zA-Z0-9\_\.\-]+$/", filter_text_input( 'tab', 'get' ) ) )
{
	nv_show_tab();
	exit;
}

$database = array();
$database['db_host_info'] = mysql_get_host_info();
$database['db_sql_version'] = $db->sql_version;
$database['db_proto_info'] = mysql_get_proto_info();
$database['server'] = $db->server;
$database['db_dbname'] = $db->dbname;
$database['db_uname'] = $db->user;

$result = $db->sql_query( 'SELECT @@session.time_zone AS `db_time_zone`, @@session.character_set_database AS `db_charset`, @@session.collation_database AS `db_collation`' );
$row = $db->sql_fetch_assoc( $result );
$db->sql_freeresult( $result );

$database['db_charset'] = $row['db_charset'];
$database['db_collation'] = $row['db_collation'];
$database['db_time_zone'] = $row['db_time_zone'];

$contents = array();
$contents['captions']['database_info'] = sprintf( $lang_module['database_info'], $database['db_dbname'] );

foreach( $database as $key => $values )
{
	$contents['database'][$lang_module[$key]] = $values;
}
unset( $database );

$contents = call_user_func( "main_theme", $contents );

$page_title = $lang_module['main'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>