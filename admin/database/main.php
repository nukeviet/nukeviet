<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:51
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

if( $nv_Request->get_bool( 'show_tabs', 'post' ) )
{
	nv_show_tables();
	exit();
}

if( $nv_Request->isset_request( 'tab', 'get' ) and preg_match( '/^(' . $db_config['prefix'] . ')\_[a-zA-Z0-9\_\.\-]+$/', $nv_Request->get_title( 'tab', 'get' ) ) )
{
	nv_show_tab();
	exit();
}

$database = array();

$database['db_host_info'] = $db->getAttribute( PDO::ATTR_DRIVER_NAME );
$database['db_sql_version'] = $db->getAttribute( PDO::ATTR_SERVER_VERSION );
$database['db_proto_info'] = $db->getAttribute( PDO::ATTR_CLIENT_VERSION );

$database['server'] = $db->server;
$database['db_dbname'] = $db->dbname;
$database['db_uname'] = $db->user;
if( $db->dbtype == 'mysql' )
{
	$row = $db->query( 'SELECT @@session.time_zone AS db_time_zone, @@session.character_set_database AS db_charset, @@session.collation_database AS db_collation' )->fetch();
	$database['db_charset'] = $row['db_charset'];
	$database['db_collation'] = $row['db_collation'];
	$database['db_time_zone'] = $row['db_time_zone'];
}

$contents = array();
$contents['captions']['database_info'] = sprintf( $lang_module['database_info'], $database['db_dbname'] );

foreach( $database as $key => $values )
{
	$contents['database'][$lang_module[$key]] = $values;
}
unset( $database );

$contents = main_theme( $contents );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';