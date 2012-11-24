<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' ); 

$page_title = $lang_module['addtotopics'];

$id_array = array();
$listid = $nv_Request->get_string( 'listid', 'get,post', '' );

if( $nv_Request->isset_request( 'topicsid', 'post' ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_topic', "listid " . $listid, $admin_info['userid'] );
	
	$topicsid = $nv_Request->get_int( 'topicsid', 'post' );
	$listid = explode( ',', $listid );
	
	foreach( $listid as $value )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET topicid='$topicsid' WHERE id='$value'";
		$result = $db->sql_query( $sql );
	}
	
	nv_del_moduleCache( $module_name );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $lang_module['topic_update_success'];
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $listid == "" )
{
	$sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `inhome`=1 ORDER BY `id` DESC LIMIT 0,20";
}
else
{
	$id_array = array_map( "intval", explode( ",", $listid ) );
	$sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `inhome`=1 AND `id` IN (" . implode( ",", $id_array ) . ") ORDER BY `id` DESC";
}

$result = $db->sql_query( $sql );

$xtpl = new XTemplate( "addtotopics.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $db->sql_numrows( $result ) )
{
	$a = 0;
	while( list( $id, $title ) = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'ROW', array(
			"class" => ( $a ++ % 2 ) ? " class=\"second\"" : "",
			"id" => $id,
			"title" => $title,
			"checked" => in_array( $id, $id_array ) ? " checked=\"checked\"" : "",
		) );

		$xtpl->parse( 'main.loop' );
	}

	$result = $db->sql_query( "SELECT `topicid`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC" );
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'TOPICSID', array( "key" => $row['topicid'], "title" => $row['title'] ) );
		$xtpl->parse( 'main.topicsid' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>