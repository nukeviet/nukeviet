<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = filter_text_input( 'title', 'post', '' );
$id = $nv_Request->get_int( 'id', 'post', 0 );

$alias = change_alias( $title );

list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` !=" . $id . " AND `alias` =  " . $db->dbescape( $alias ) . "" ) );

if( intval( $number ) > 0 )
{
	$result = $db->sql_query( "SHOW TABLE STATUS WHERE `Name`='" . NV_PREFIXLANG . "_" . $module_data . "'" );
	$item = $db->sql_fetch_assoc( $result );
	$db->sql_freeresult( $result );
	if( isset( $item['Auto_increment'] ) )
	{
		$alias = $alias . "-" . $item['Auto_increment'];
	}
	else
	{
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "`" ) );
		$weight = intval( $weight ) + 1;
		$alias = $alias . "-" . $weight;
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $alias;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>