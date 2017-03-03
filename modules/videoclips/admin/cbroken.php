<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'ischecked', 'post' ) )
{
	$ischecked = $nv_Request->get_int( 'ischecked', 'post', 0 );
	$content = filter_text_input( 'content', 'post', '', 1 );
	if ( empty( $content ) ) die( "ERROR" );
	$content = nv_nl2br( $content );

	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comm` SET 
    `content`=" . $db->dbescape( $content ) . ", 
    `ischecked`=1, 
    `broken`=0 
    WHERE `id`=" . $ischecked;
	$db->sql_query( $query );
	die( "OK" );
}

if ( $nv_Request->isset_request( 'delcomm', 'post' ) )
{
	$delcomm = $nv_Request->get_int( 'delcomm', 'post', 0 );
	$sql = "SELECT `cid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `id`=" . $delcomm;
	$result = $db->sql_query( $sql );
	list( $cid ) = $db->sql_fetchrow( $result );

	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `id`=" . $delcomm;
	$db->sql_query( $sql );

	$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `cid`=" . $cid;
	$result = $db->sql_query( $sql );
	list( $count ) = $db->sql_fetchrow( $result );

	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hit` SET `comment`=" . $count . " WHERE `cid`=" . $cid;
	$db->sql_query( $query );
	die( "OK" );
}

$page_title = $lang_module['cbroken'];

$xtpl = new XTemplate( "cbroken.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'MODURL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 10;

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.username, b.full_name, c.title 
    FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` a, 
    `" . NV_USERS_GLOBALTABLE . "` b, 
    `" . NV_PREFIXLANG . "_" . $module_data . "_clip` c 
    WHERE a.broken!=0 AND a.ischecked=0 AND a.userid=b.userid AND a.cid=c.id 
    ORDER BY a.broken DESC LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

$res = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $res );

if ( $all_page )
{
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		if ( empty( $row['full_name'] ) ) $row['full_name'] = $row['username'];
		$row['content'] = nv_br2nl( $row['content'] );
		$row['userUrl'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['userid'];
		$row['clipUrl'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&edit&id=" . $row['cid'];
		$row['pubDate'] = nv_ucfirst( nv_strtolower( nv_date( "d/m/Y, H:i", $row['posttime'] ) ) );
		$xtpl->assign( 'DATA', $row );
		$xtpl->parse( 'main.loop' );
	}
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( ! empty( $generate_page ) )
{
	$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
}
elseif ( $page )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	exit();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>