<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['topic_page'];
$set_active_op = "topics";

$topicid = $nv_Request->get_int( 'topicid', 'get' );
if( ! $topicid )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topics" );
	die();
}

$global_array_cat = array();

$sql = "SELECT `catid`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while( list( $catid_i, $alias_i ) = $db->sql_fetchrow( $result ) )
{
	$global_array_cat[$catid_i] = array(
		"alias" => $alias_i,
	);
}

$sql = "SELECT `id`, `catid`, `alias`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `topicid`='" . $topicid . "' ORDER BY `id` ASC";
$result = $db->sql_query( $sql );

$xtpl = new XTemplate( "topicsnews.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TOPICID', $topicid );

if( $db->sql_numrows( $result ) )
{
	$a = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{		
		$row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['catid']]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
		$row['class'] = ( $a % 2 ) ? " class=\"second\"" : "";
		$row['delete'] = nv_link_edit_page( $row['id'] );
		
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.data.loop' );
		++ $a;
	}
	
	$xtpl->assign( 'URL_DELETE', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicdelnews" );
	$xtpl->parse( 'main.data' );
}
else
{
	$xtpl->parse( 'main.empty' );
}

$db->sql_freeresult();

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>