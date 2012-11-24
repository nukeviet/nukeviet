<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['comment_edit_title'];
$cid = $nv_Request->get_int( 'cid', 'get' );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_comment', "id " . $cid, $admin_info['userid'] );
	$sql = "SELECT a.id, a.title, a.listcatid, a.alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_comments` b ON a.id=b.id WHERE b.cid='" . $cid . "'";

	list( $id, $title, $listcatid, $alias ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
	if( $id > 0 )
	{
		$delete = $nv_Request->get_int( 'delete', 'post', 0 );
		if( $delete )
		{
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `cid`=" . $cid );
		}
		else
		{
			$content = nv_nl2br( filter_text_textarea( 'content', '', NV_ALLOWED_HTML_TAGS ) );
			$active = $nv_Request->get_int( 'active', 'post', 0 );
			$status = ( $status == 1 ) ? 1 : 0;
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET `content`=" . $db->dbescape( $content ) . ", `status`=" . $active . " WHERE `cid`=" . $cid );
		}

		// Cap nhat lai so luong comment duoc kich hoat
		$array_catid = explode( ",", $listcatid );
		list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
		$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
		$db->sql_query( $query );
		foreach( $array_catid as $catid_i )
		{
			$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
			$db->sql_query( $query );
		}

		// Het Cap nhat lai so luong comment duoc kich hoat
	}
	header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=comment' );
	die();
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid=" . $cid;
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) == 0 )
{
	header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=comment' );
	die();
}

$row = $db->sql_fetchrow( $result );
$row['content'] = nv_htmlspecialchars( nv_br2nl( $row['content'] ) );

$row['status'] = ( $row['status'] ) ? "checked=\"checked\"" : "";

$xtpl = new XTemplate( "comment_edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'CID', $cid );
$xtpl->assign( 'ROW', $row );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>