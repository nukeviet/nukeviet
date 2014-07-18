<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$ac = $nv_Request->get_string( 'ac', 'post,get', '' );

if( empty( $user_info ) ) die( 'NO_0_' . $lang_module['wishlist_guest'] );
if( empty( $id ) or empty( $ac ) ) die( 'NO_0_' . $lang_module['wishlist_error'] );

if( $ac == 'add' )
{
	$wishlist = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_wishlist WHERE user_id = ' . $user_info['userid'] )->fetch();
	if( sizeof( $wishlist ) > 1 )
	{
		$listid = $wishlist['listid'];
		if( ! empty( $listid ) )
		{
			$listid = explode( ',', $listid );
		}
		else
		{
			$listid = array();
		}
		$count = count( $listid );

		if( ! in_array( $id, $listid ) )
		{
			$listid[] = $id;
			$listid = implode( ',', $listid );
			
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_wishlist SET listid = ' . $db->quote( $listid ) . ' WHERE wid = ' . $wishlist['wid'];
			if( ! $db->query( $sql ) )
			{
				die( 'NO_0_' . $lang_module['wishlist_error'] );
			} 
			else 
			{
				$count += 1;
			}	
		}
		else
		{
			die( 'NO_0_' . $lang_module['wishlist_exits'] );
		}
	}
	else
	{
		$count = 1;
		$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_wishlist (user_id, listid) VALUES (' . $user_info['userid'] . ', ' . $id . ' )';
		if( ! $db->query( $sql ) ) die( 'NO_0_' . $lang_module['wishlist_error'] );
	}
	
	nv_del_moduleCache( $module_name );
}
elseif( $ac == 'del' )
{
	$listid = explode( ',', $listid );
	$count = count( $listid );
	
	if( in_array( $id, $listid ) )
	{
		foreach( $listid as $key => $rid)
		{
			if( $rid == $id ) unset( $listid[$key] );
		}
		
		if( count( $listid ) > 0 )
		{
			$listid = implode( ',', $listid );
		}
		else
		{
			$listid = '';
		}
		
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_wishlist SET listid = ' . $db->quote( $listid ) . ' WHERE user_id = ' . $user_info['userid'];
		if( ! $db->query( $sql ) )
		{
			die( 'NO_0_' . $lang_module['wishlist_error'] );
		} 
		else
		{
			$count -= 1;
		}
		nv_del_moduleCache( $module_name );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $count . '_' . ( $ac == 'add' ? $lang_module['wishlist_add_success'] : $lang_module['wishlist_del_item_success'] );
include NV_ROOTDIR . '/includes/footer.php';