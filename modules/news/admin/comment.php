<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['comment'];

$global_array_cat = array();
$global_array_cat[0] = array( 'alias' => 'Other' );

$sql = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$result = $db->query( $sql );
while( list( $catid_i, $alias_i ) = $result->fetch( 3 ) )
{
	$global_array_cat[$catid_i] = array( 'alias' => $alias_i );
}

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 20;

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_PREFIXLANG . '_' . $module_data . '_comments a' )
	->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_rows b ON (a.id=b.id) LEFT JOIN ' . $db_config['dbsystem'] . '.' . NV_USERS_GLOBALTABLE . ' c ON (a.userid =c.userid)' );

$all_page = $db->query( $db->sql() )->fetchColumn();

$db->select( 'a.cid, a.content, a.post_email, a.status, b.id, b.title, b.listcatid, b.alias, c.userid, c.email' )
	->order( 'a.cid DESC' )
	->limit( $per_page )
	->offset( $page );

$result = $db->query( $db->sql() );
$array = array();
while( list( $cid, $content, $email, $status, $id, $title, $listcatid, $alias, $userid, $user_email ) = $result->fetch( 3 ) )
{
	$arr_listcatid = explode( ',', $listcatid );
	$catid_i = end( $arr_listcatid );

	if( $userid > 0 )
	{
		$email = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid . '"> ' . $user_email . '</a>';
	}

	$array[$cid] = array(
		'cid' => $cid,
		'email' => $email,
		'content' => $content,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id,
		'title' => $title,
		'status' => ( $status == 1 ) ? $lang_module['comment_enable'] : $lang_module['comment_disable'],
		'linkedit' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_comment&amp;cid=' . $cid,
		'linkdelete' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del_comment&amp;list=' . $cid
	);
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

foreach( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>