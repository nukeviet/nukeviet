<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2012 VINADES.,JSC. All rights reserved
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

$page_title = $module_info['funcs'][$op]['func_custom_name'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['listusers'];

if( $global_config['whoviewuser'] == 2 and ! defined( "NV_IS_ADMIN" ) )
{
	$nv_redirect = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
	user_info_exit_redirect( $lang_module['allow_admin'], $nv_redirect );
}
elseif( $global_config['whoviewuser'] == 1 and ! defined( 'NV_IS_USER' ) )
{
	$nv_redirect = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
	user_info_exit_redirect( $lang_module['allow_user'], $nv_redirect );
}
else
{
	// Them vao tieu de
	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $lang_module['listusers'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op,
		);
	//xem chi tiet thanh vien
	if( isset( $array_op[1] ) && ! empty( $array_op[1] ) )
	{
		$md5 = "";
		unset( $matches );
		if( preg_match( "/^(.*)\-([a-z0-9]{32})$/", $array_op[1], $matches ) ) $md5 = $matches[2];
		if( ! empty( $md5 ) )
		{
			$result = $db->sql_query( "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `md5username` = " . $db->dbescape( $md5 ) );
			if( $db->sql_numrows( $result ) > 0 )
			{
				$item = $db->sql_fetch_assoc( $result );
				if( change_alias( $item['username'] ) != $matches[1] )
				{
					// Chuyen ve trang module ngay khong can thong bao
					Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
					exit();
				}
				// Them vao tieu de
				$array_mod_title[] = array(
					'catid' => 0,
					'title' => $item['username'],
					'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/" . change_alias( $item['username'] ) . "-" . $item['md5username'],
					);

				$contents = nv_memberslist_detail_theme( $item );
			}
			else
			{
				// Chuyen ve trang module ngay khong can thong bao
				Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
				exit();
			}
		}

		include ( NV_ROOTDIR . "/includes/header.php" );
		echo nv_site_theme( $contents );
		include ( NV_ROOTDIR . "/includes/footer.php" );
		exit();
	} //danh sach thanh vien
	else
	{
		$orderby = $nv_Request->get_string( 'orderby', 'get', 'username' );
		$sortby = $nv_Request->get_string( 'sortby', 'get', 'DESC' );
		$page = $nv_Request->get_int( 'page', 'get', 0 );

		// Kiem tra du lieu hop chuan
		if( ( ! empty( $orderby ) and ! in_array( $orderby, array(
			'username',
			'gender',
			'regdate' ) ) ) or ( ! empty( $sortby ) and ! in_array( $sortby, array( 'DESC', 'ASC' ) ) ) )
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}

		$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=" . $orderby . "&sortby=" . $sortby;

		$per_page = 25;
		$array_order = array(
			"username" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=username&sortby=" . $sortby,
			"gender" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=gender&sortby=" . $sortby,
			"regdate" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=regdate&sortby=" . $sortby,

			);

		foreach( $array_order as $key => $link )
		{
			if( $orderby == $key )
			{
				$sortby_new = ( $sortby == "DESC" ) ? "ASC" : "DESC";
				$array_order_new[$key] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=" . $key . "&sortby=" . $sortby_new;
			}
			else
			{
				$array_order_new[$key] = $link;
			}
		}

		$result = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS `userid`, `username`, `md5username`, `full_name`, `photo`, `gender`, `yim`, `regdate` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `active`=1 ORDER BY " . $orderby . " " . $sortby . " LIMIT " . $page . "," . $per_page );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		$users_array = array();

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			if( ! empty( $item['photo'] ) and file_exists( NV_ROOTDIR . "/" . $item['photo'] ) )
			{
				$item['photo'] = NV_BASE_SITEURL . $item['photo'];
			}
			else
			{
				$item['photo'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no_avatar.jpg";
			}

			$item['regdate'] = nv_date( "d/m/Y", $item['regdate'] );
			$item['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=memberlist/" . change_alias( $item['username'] ) . "-" . $item['md5username'];
			$item['gender'] = ( $item['gender'] == "M" ) ? $lang_module['male'] : ( $item['gender'] == 'F' ? $lang_module['female'] : $lang_module['na'] );

			$users_array[$item['userid']] = $item;
		}

		// Khong cho dat trang tuy tien
		if( empty( $users_array ) and $page > 0 )
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}

		// Them vao tieu de trang
		if( ! empty( $orderby ) )
		{
			$page_title .= " " . sprintf( $lang_module['listusers_sort_by'], $lang_module['listusers_sort_by_' . $orderby], $lang_module['listusers_order_' . $sortby] );
		}

		// Tieu de khi phan trang
		if( $page > 0 )
		{
			$page_title .= " " . NV_TITLEBAR_DEFIS . " " . sprintf( $lang_module['page'], ceil( $page / $per_page ) );
		}

		$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

		$db->sql_freeresult( $result );
		unset( $result, $item );

		$contents = nv_memberslist_theme( $users_array, $array_order_new, $generate_page );
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>