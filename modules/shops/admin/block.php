<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

 if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['block'];
$set_active_op = 'blockcat';

$sql = 'SELECT bid, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query( $sql );

$array_block = array();
while( list( $bid_i, $title_i ) = $result->fetch( 3 ) )
{
	$array_block[$bid_i] = $title_i;
}
if( empty($array_block) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blockcat' );
}

$cookie_bid = $nv_Request->get_int( 'int_bid', 'cookie', 0 );
if( empty( $cookie_bid ) or ! isset( $array_block[$cookie_bid] ) )
{
	$cookie_bid = 0;
}

$bid = $nv_Request->get_int( 'bid', 'get,post', $cookie_bid );
if( ! in_array( $bid, array_keys( $array_block ) ) )
{
	$bid_array_id = array_keys( $array_block );
	$bid = $bid_array_id[0];
}

if( $cookie_bid != $bid )
{
	$nv_Request->set_Cookie( 'int_bid', $bid, NV_LIVE_COOKIE_TIME );
}
$page_title = $array_block[$bid];

if( $nv_Request->isset_request( 'checkss,idcheck', 'post' ) and $nv_Request->get_string( 'checkss', 'post' ) == md5( session_id() ) )
{
	$id_array = array_map( 'intval', $nv_Request->get_array( 'idcheck', 'post' ) );
	foreach( $id_array as $id )
	{
		$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES ('" . $bid . "', '" . $id . "', '0')" );
	}
	nv_news_fix_block( $bid );
	nv_del_moduleCache( $module_name );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid );
	die();
}

$xtpl = new XTemplate( 'block.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSESS', md5( session_id() ) );

$xtpl->assign( 'BLOCK_LIST', nv_show_block_list( $bid ) );

$id_array = array();
$listid = $nv_Request->get_string( 'listid', 'get', '' );

if( $listid == '' )
{
	$db->sqlreset()->select( 'id, ' . NV_LANG_DATA . '_title' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'inhome=1 AND id NOT IN(SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE bid=' . $bid . ')' )->order( 'id DESC' )->limit( 20 );
	$sql = $db->sql();

}
else
{
	$id_array = array_map( 'intval', explode( ',', $listid ) );
	$sql = 'SELECT id, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE inhome=1 AND id IN (' . implode( ',', $id_array ) . ') ORDER BY id DESC';
}

$result = $db->query( $sql );
if( $result->rowCount() )
{
	$a = 0;
	while( list( $id, $title ) = $result->fetch( 3 ) )
	{
		$xtpl->assign( 'ROW', array(
			'class' => ( $a % 2 ) ? ' class="second"' : '',
			'id' => $id,
			'checked' => in_array( $id, $id_array ) ? ' checked="checked"' : '',
			'title' => $title
		) );

		$xtpl->parse( 'main.loop' );
		++$a;
	}

	foreach( $array_block as $xbid => $blockname )
	{
		$xtpl->assign( 'BID', array(
			'key' => $xbid,
			'title' => $blockname,
			'selected' => ( $xbid == $bid ) ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.bid' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';