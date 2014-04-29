<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_COMMENT', true );

$per_page_comment = 5;

/**
 * nv_comment_module()
 *
 * @param mixed $id
 * @param mixed $module
 * @param mixed $page
 * @return
 */
function nv_comment_data( $module, $area, $id, $allowed, $page, $sortcomm, $base_url )
{
	global $db, $module_name, $global_config, $module_config, $db_config, $per_page_comment;

	$comment_array = array();
	$_where = 'a.module=' . $db->quote( $module );
	if( $area )
	{
		$_where .= ' AND a.area= ' . $area;
	}
	$_where .= ' AND a.id= ' . $id . ' AND a.status=1';

	$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comments a' )->join( 'LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid' )->where( $_where );

	$num_items = $db->query( $db->sql() )->fetchColumn();
	if( $num_items )
	{
		$emailcomm = $module_config[$module]['emailcomm'];
		$db->select( 'a.cid, a.content, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.email, b.full_name, b.photo, b.view_mail' )->limit( $per_page_comment )->offset( ( $page - 1 ) * $per_page_comment );

		if( $sortcomm == 1 )
		{
			$db->order( 'a.cid ASC' );
		}
		elseif( $sortcomm == 2 )
		{
			$db->order( 'a.likes DESC, a.cid DESC' );
		}
		else
		{
			$db->order( 'a.cid DESC' );
		}
		$session_id = session_id() . '_' . $global_config['sitekey'];
		$result = $db->query( $db->sql() );
		while( $row = $result->fetch() )
		{
			if( $row['userid'] > 0 )
			{
				$row['post_email'] = $row['email'];
				$row['post_name'] = $row['full_name'];
			}
			$row['check_like'] = md5( $row['cid'] . '_' . $session_id );
			$row['post_email'] = ( $emailcomm ) ? $row['post_email'] : '';
			$comment_array[] = $row;
		}
		$result->closeCursor();
		unset( $row, $result );

		$generate_page = nv_generate_page( $base_url, $num_items, $per_page_comment, $page );
	}
	else
	{
		$generate_page = '';
	}
	return array( 'comment' => $comment_array, 'page' => $generate_page );
}