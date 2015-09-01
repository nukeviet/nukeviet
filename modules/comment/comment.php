<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$per_page_comment = ( defined( 'NV_PER_PAGE_COMMENT' ) ) ? NV_PER_PAGE_COMMENT : 5;

/**
 * nv_comment_module()
 *
 * @param mixed $id
 * @param mixed $module
 * @param mixed $page
 * @return
 *
 */
function nv_comment_data( $module, $area, $id, $allowed, $page, $sortcomm, $base_url )
{
	global $db, $global_config, $module_config, $db_config, $per_page_comment;

	$comment_array = array();
	$_where = 'a.module=' . $db->quote( $module );
	if( $area )
	{
		$_where .= ' AND a.area= ' . $area;
	}
	$_where .= ' AND a.id= ' . $id . ' AND a.status=1 AND a.pid=0';

	$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comment a' )->join( 'LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid' )->where( $_where );

	$num_items = $db->query( $db->sql() )->fetchColumn();
	if( $num_items )
	{
		$emailcomm = $module_config[$module]['emailcomm'];
		$db->select( 'a.cid, a.pid, a.content, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.email, b.first_name, b.last_name, b.photo, b.view_mail' )->limit( $per_page_comment )->offset( ( $page - 1 ) * $per_page_comment );

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
		$comment_list_id = array();
		while( $row = $result->fetch() )
		{
			$comment_list_id[] = $row['cid'];
			if( $row['userid'] > 0 )
			{
				$row['post_email'] = $row['email'];
				$row['post_name'] = $row['first_name'];
			}
			$row['check_like'] = md5( $row['cid'] . '_' . $session_id );
			$row['post_email'] = ( $emailcomm ) ? $row['post_email'] : '';
			$comment_array[$row['cid']] = $row;
		}
		if( ! empty( $comment_list_id ) )
		{
			foreach( $comment_list_id as $cid )
			{
				$comment_array[$cid]['subcomment'] = nv_comment_get_reply( $cid, $module, $session_id, $sortcomm );
			}
			$result->closeCursor();
			unset( $row, $result );
			$generate_page = nv_generate_page( $base_url, $num_items, $per_page_comment, $page, true, true, 'nv_urldecode_ajax', 'idcomment' );
		}
		else
		{
			$generate_page = '';
		}
		return array(
			'comment' => $comment_array,
			'page' => $generate_page
		);
	}
}

function nv_comment_get_reply( $cid, $module, $session_id, $sortcomm )
{
	global $db, $module_config;
	$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comment a' )->join( 'LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid' )->where( 'a.pid=' . $cid . ' AND a.status=1' );

	$data_reply_comment = array();

	$num_items_sub = $db->query( $db->sql() )->fetchColumn();
	if( $num_items_sub )
	{
		$emailcomm = $module_config[$module]['emailcomm'];
		$db->select( 'a.cid, a.pid, a.content, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.email, b.first_name, b.last_name, b.photo, b.view_mail' );

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
		$result = $db->query( $db->sql() );
		$comment_list_id_reply = array();
		while( $row = $result->fetch() )
		{
			$row['check_like'] = md5( $row['cid'] . '_' . $session_id );
			$row['post_email'] = ( $emailcomm ) ? $row['post_email'] : '';
			$data_reply_comment[$row['cid']] = $row;
			$data_reply_comment[$row['cid']]['subcomment'] = nv_comment_get_reply( $row['cid'], $module, $session_id, $sortcomm );
		}
	}
	return $data_reply_comment;
}

function nv_comment_module( $module, $checkss, $area, $id, $allowed, $page, $status_comment = '' )
{
	global $module_config, $nv_Request, $lang_module_comment, $module_info, $client_info, $per_page_comment;

	// Kiểm tra module có được Sử dụng chức năng bình luận
	if( ! empty( $module ) and isset( $module_config[$module]['activecomm'] ) )
	{
		if( $id > 0 and $module_config[$module]['activecomm'] == 1 and $checkss == md5( $module . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CACHE_PREFIX ) )
		{
			$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&perpage=' . $per_page_comment;

			// Kiểm tra quyền xem bình luận
			$form_login = array(
				'display' => 0
			);
			$view_comm = nv_user_in_groups( $module_config[$module]['view_comm'] );

			$allowed_comm = nv_user_in_groups( $allowed );
			if( ! ( $view_comm and $allowed_comm ) and ! defined( 'NV_IS_USER' ) )
			{
				$form_login['display'] = 1;
				$allowed_tmp = explode( ',', $allowed );
				if( sizeof( $allowed_tmp ) == 1 )
				{
					$_in_admin = array_intersect( $allowed_tmp, array( 1, 2, 3 ) );
					if( ! empty( $_in_admin ) )
					{
						$form_login['display'] = 0;
					}
					else
					{
						$form_login['list_groups'] = $allowed;
					}
				}
				else
				{
					$form_login['list_groups'] = $allowed;
				}
			}

			$page_title = $module_info['custom_title'];
			$key_words = $module_info['keywords'];
			$array_data = array();

			$sortcomm_old = $nv_Request->get_int( 'sortcomm', 'cookie', $module_config[$module]['sortcomm'] );
			$sortcomm = $nv_Request->get_int( 'sortcomm', 'post,get', $sortcomm_old );
			if( $sortcomm < 0 or $sortcomm > 2 )
			{
				$sortcomm = 0;
			}
			if( $sortcomm_old != $sortcomm )
			{
				$nv_Request->set_Cookie( 'sortcomm', $sortcomm, NV_LIVE_COOKIE_TIME );
			}
			$is_delete = false;
			if( defined( 'NV_IS_ADMIN' ) )
			{
				$is_delete = true;
			}
			elseif( defined( 'NV_IS_ADMIN' ) )
			{
				$adminscomm = explode( ',', $module_config[$module]['adminscomm'] );
				if( in_array( $admin_info['admin_id'], $adminscomm ) )
				{
					$is_delete = true;
				}
			}

			if( file_exists( NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php' ) )
			{
				require NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php';
			}
			else
			{
				require NV_ROOTDIR . '/modules/comment/language/en.php';
			}
			$lang_module_comment = $lang_module;
			if( $view_comm )
			{
				$comment_array = nv_comment_data( $module, $area, $id, $allowed_comm, $page, $sortcomm, $base_url );
				$comment = nv_comment_module_data( $module, $comment_array, $is_delete );
			}
			else
			{
				$comment = '';
			}
			return nv_theme_comment_module( $module, $area, $id, $allowed, $checkss, $comment, $sortcomm, $base_url, $form_login, $status_comment );
		}
		else
		{
			return '';
		}
	}
}

/**
 *
 * @param string $module
 * @param integer $area
 * @param integer $id
 * @param int $allowed_comm
 * @param string $checkss
 * @param string $comment
 * @param int $sortcomm
 * @param string $base_url
 * @param boolean $form_login
 * @param int $status_comment
 * @return string
 */
function nv_theme_comment_module( $module, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $base_url, $form_login, $status_comment = '' )
{
	global $global_config, $module_file, $module_data, $module_config, $admin_info, $user_info, $lang_global, $client_info, $lang_module_comment, $module_name;

	$template = file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/main.tpl' ) ? $global_config['module_theme'] : 'default';

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment' );
	$xtpl->assign( 'LANG', $lang_module_comment );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'TEMPLATE', $template );
	$xtpl->assign( 'CHECKSS_COMM', $checkss );
	$xtpl->assign( 'MODULE_COMM', $module );
	$xtpl->assign( 'MODULE_DATA', $module_data );
	$xtpl->assign( 'AREA_COMM', $area );
	$xtpl->assign( 'ID_COMM', $id );
	$xtpl->assign( 'ALLOWED_COMM', $allowed_comm );
	$xtpl->assign( 'BASE_URL_COMM', $base_url );

	if( defined( 'NV_COMM_ID' ) )
	{
		// Check call module js file
		if( file_exists( NV_ROOTDIR . '/themes/' . $template . '/js/comment.js' ) )
		{
			$xtpl->parse( 'main.header.jsfile' );
		}

		// Check call module css file
		if( file_exists( NV_ROOTDIR . '/themes/' . $template . '/css/comment.css' ) )
		{
			$xtpl->parse( 'main.header.cssfile' );
		}

		$xtpl->parse( 'main.header' );
	}

	// Order by comm
	for( $i = 0; $i <= 2; ++$i )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $i,
			'title' => $lang_module_comment['sortcomm_' . $i],
			'selected' => ( $i == $sortcomm ) ? ' selected="selected"' : ''
		) );

		$xtpl->parse( 'main.sortcomm' );
	}

	if( ! empty( $comment ) )
	{
		$xtpl->assign( 'COMMENTCONTENT', $comment );
		$xtpl->parse( 'main.showContent' );
	}

	$allowed_comm = nv_user_in_groups( $allowed_comm );
	if( $allowed_comm )
	{
		if( defined( 'NV_IS_USER' ) )
		{
			$xtpl->assign( 'NAME', $user_info['full_name'] );
			$xtpl->assign( 'EMAIL', $user_info['email'] );
			$xtpl->assign( 'DISABLED', ' disabled="disabled"' );
		}
		else
		{
			$xtpl->assign( 'NAME', '' );
			$xtpl->assign( 'EMAIL', '' );
			$xtpl->assign( 'DISABLED', '' );
		}

		$captcha = intval( $module_config[$module]['captcha'] );
		$show_captcha = true;
		if( $captcha == 0 )
		{
			$show_captcha = false;
		}
		elseif( $captcha == 1 and defined( 'NV_IS_USER' ) )
		{
			$show_captcha = false;
		}
		elseif( $captcha == 2 and defined( 'NV_IS_MODADMIN' ) )
		{
			if( defined( 'NV_IS_SPADMIN' ) )
			{
				$show_captcha = false;
			}
			else
			{
				$adminscomm = explode( ',', $module_config[$module]['adminscomm'] );
				if( in_array( $admin_info['admin_id'], $adminscomm ) )
				{
					$show_captcha = false;
				}
			}
		}

		if( $show_captcha )
		{
			$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
			$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
			$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
			$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
			$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
			$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
			$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_FILES_DIR . '/images/refresh.png' );
			$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME );
			$xtpl->parse( 'main.allowed_comm.captcha' );
		}
		else
		{
			$xtpl->assign( 'GFX_NUM', 0 );
		}

		if( ! empty( $status_comment ) )
		{
			$status_comment = nv_base64_decode( $status_comment );
			$xtpl->assign( 'STATUS_COMMENT', $status_comment );
			$xtpl->parse( 'main.allowed_comm.comment_result' );
		}

		$xtpl->parse( 'main.allowed_comm' );
	}
	elseif( $form_login['display'] )
	{
		// Ajax login
		if( $form_login['list_groups'] == 4 )
		{
			$xtpl->parse( 'main.form_login.message_login' );
		}
		else
		{
			$list_groups_name = '';
			$list_groups = nv_groups_list_pub();
			$form_login['list_groups'] = explode( ',', $form_login['list_groups'] );
			$i = 0;
			foreach( $form_login['list_groups'] as $group_id )
			{
				if( isset( $list_groups[$group_id] ) )
				{
					if( $i == 0 )
					{
						$list_groups_name .= $list_groups[$group_id];
					}
					else
					{
						$list_groups_name .= ', ' . $list_groups[$group_id];
					}
					$i++;
				}
			}
			$url_groups = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo';
			$xtpl->assign( 'LANG_REG_GROUPS', sprintf( $lang_module_comment['comment_register_groups'], $list_groups_name, $url_groups ) );
			$xtpl->parse( 'main.form_login.message_register_group' );
		}
		$xtpl->parse( 'main.form_login' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_comment_module_data( $module, $comment_array, $is_delete )
{
	global $global_config, $module_file, $module_config, $lang_module_comment;

	$template = file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/comment.tpl' ) ? $global_config['module_theme'] : 'default';

	if( ! empty( $comment_array['comment'] ) )
	{
		$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment' );
		$xtpl->assign( 'TEMPLATE', $template );
		$xtpl->assign( 'LANG', $lang_module_comment );

		foreach( $comment_array['comment'] as $comment_array_i )
		{
			if( ! empty( $comment_array_i['subcomment'] ) )
			{
				$comment_array_reply = nv_comment_module_data_reply( $module, $comment_array_i['subcomment'], $is_delete );
				$xtpl->assign( 'CHILDREN', $comment_array_reply );
				$xtpl->parse( 'main.detail.children' );
			}
			$comment_array_i['post_time'] = nv_date( 'd/m/Y H:i', $comment_array_i['post_time'] );

			if( ! empty( $comment_array_i['photo'] ) && file_exists( NV_ROOTDIR . '/' . $comment_array_i['photo'] ) )
			{
				$comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
			}
			elseif( is_file( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png' ) )
			{
				$comment_array_i['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
			}
			else
			{
				$comment_array_i['photo'] = NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.png';
			}

			if( ! empty( $comment_array_i['userid'] ) )
			{
				$comment_array_i['post_name'] = nv_show_name_user( $comment_array_i['first_name'], $comment_array_i['last_name'] );
			}

			$xtpl->assign( 'COMMENT', $comment_array_i );

			if( $module_config[$module]['emailcomm'] and ! empty( $comment_array_i['post_email'] ) )
			{
				$xtpl->parse( 'main.detail.emailcomm' );
			}

			if( $is_delete )
			{
				$xtpl->parse( 'main.detail.delete' );
			}

			$xtpl->parse( 'main.detail' );
		}
		if( ! empty( $comment_array['page'] ) )
		{
			$xtpl->assign( 'PAGE', $comment_array['page'] );
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	else
	{
		return '';
	}
}

function nv_comment_module_data_reply( $module, $comment_array, $is_delete )
{
	global $global_config, $module_file, $module_config, $lang_module_comment;

	$template = file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/comment.tpl' ) ? $global_config['module_theme'] : 'default';

	$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment' );
	$xtpl->assign( 'TEMPLATE', $template );
	$xtpl->assign( 'LANG', $lang_module_comment );

	foreach( $comment_array as $comment_array_i )
	{
		if( ! empty( $comment_array_i['subcomment'] ) )
		{
			$comment_array_reply = nv_comment_module_data_reply( $module, $comment_array_i['subcomment'], $is_delete );
			$xtpl->assign( 'CHILDREN', $comment_array_reply );
			$xtpl->parse( 'children.detail.children' );
		}
		$comment_array_i['post_time'] = nv_date( 'd/m/Y H:i', $comment_array_i['post_time'] );

		if( ! empty( $comment_array_i['photo'] ) && file_exists( NV_ROOTDIR . '/' . $comment_array_i['photo'] ) )
		{
			$comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
		}
		else
		{
			$comment_array_i['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
		}

		if( ! empty( $comment_array_i['userid'] ) )
		{
			$comment_array_i['post_name'] = nv_show_name_user( $comment_array_i['first_name'], $comment_array_i['last_name'] );
		}

		$xtpl->assign( 'COMMENT', $comment_array_i );

		if( $module_config[$module]['emailcomm'] and ! empty( $comment_array_i['post_email'] ) )
		{
			$xtpl->parse( 'children.detail.emailcomm' );
		}

		if( $is_delete )
		{
			$xtpl->parse( 'children.detail.delete' );
		}

		$xtpl->parse( 'children.detail' );
	}
	$xtpl->parse( 'children' );
	return $xtpl->text( 'children' );
}