<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['edit_title'];
$cid = $nv_Request->get_int( 'cid', 'get,post' );
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_comments WHERE cid=' . $cid;
$row = $db->query( $sql )->fetch();

if( empty( $row ) or ! isset( $site_mod_comm[$row['module']] ) )
{
	header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$delete = $nv_Request->get_int( 'delete', 'post', 0 );
	if( $delete )
	{
		$count = $db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE cid=' . $cid );
	}
	else
	{
		$content = $nv_Request->get_textarea( 'content', '', NV_ALLOWED_HTML_TAGS, 1 );
		$active = $nv_Request->get_int( 'active', 'post', 0 );
		$active = ( $active == 1 ) ? 1 : 0;

		$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_comments SET content= :content, status=' . $active . ' WHERE cid=' . $cid );
		$stmt->bindParam( ':content', $content, PDO::PARAM_STR );
		$stmt->execute();
		$count = $stmt->rowCount();
	}

	if( $count )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_title'] . ': ' . $row['module'] . ', id: ' . $row['id'] . ', cid: ' . $row['cid'], $row['content'], $admin_info['userid'] );

		if( isset( $site_mods[$row['module']] ) )
		{
			$mod_info = $site_mods[$row['module']];
			if( file_exists( NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
			}
		}
	}
	header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$row['content'] = nv_htmlspecialchars( nv_br2nl( $row['content'] ) );
$row['status'] = ( $row['status'] ) ? 'checked="checked"' : '';

$xtpl = new XTemplate( 'edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CID', $cid );
$xtpl->assign( 'ROW', $row );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';