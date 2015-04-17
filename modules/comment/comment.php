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

	$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comments a' )->join( 'LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid' )->where( $_where );

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
		return array( 'comment' => $comment_array, 'page' => $generate_page );
	}
}

function nv_comment_get_reply( $cid, $module, $session_id, $sortcomm )
{
	global $db, $module_config;
	$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_comments a' )->join( 'LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid' )->where( 'a.pid=' . $cid . ' AND a.status=1' );

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

function nv_comment_module( $module, $url_comment, $checkss, $area, $id, $allowed, $page )
{
	global $module_config, $nv_Request, $lang_module_comment, $module_info, $client_info, $per_page_comment;
	// Kiểm tra module có được Sử dụng chức năng bình luận
	if( ! empty( $module ) and isset( $module_config[$module]['activecomm'] ) )
	{
		if( $id > 0 and $module_config[$module]['activecomm'] == 1 )
		{
			$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&perpage=' . $per_page_comment . '&url_comment=' . $url_comment;

			// Kiểm tra quyền xem bình luận
			$form_login = 0;
			$view_comm = nv_user_in_groups( $module_config[$module]['view_comm'] );

			$allowed_comm = nv_user_in_groups( $allowed );
			if( ! ( $view_comm and $allowed_comm ) and ! defined( 'NV_IS_USER' ) )
			{
				$form_login = 1;
			}
			$array_data = array();

			$page_title = $module_info['custom_title'];
			$key_words = $module_info['keywords'];
			if( $client_info['browser']['key'] == 'chrome' )
			{
				$global_config['mudim_showpanel'] = 0;
			}
			else
			{
				$global_config['mudim_active'] = 0;
			}

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
			return nv_theme_comment_module( $module, $url_comment, $area, $id, $allowed, $checkss, $comment, $sortcomm, $base_url, $form_login );
		}
		else
		{
			return '';
		}
	}
}

/**
 * nv_theme_comment_main()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_comment_module( $module, $url_comment, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $base_url, $form_login )
{
	global $global_config, $module_file, $module_data, $module_config, $module_info, $admin_info, $user_info, $lang_global, $client_info, $lang_module_comment, $module_name;

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/comment' );
	$xtpl->assign( 'LANG', $lang_module_comment );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	$xtpl->assign( 'CHECKSS_COMM', $checkss );
	$xtpl->assign( 'MODULE_COMM', $module );
	$xtpl->assign( 'MODULE_DATA', $module_data );
	$xtpl->assign( 'AREA_COMM', $area );
	$xtpl->assign( 'ID_COMM', $id );
	$xtpl->assign( 'ALLOWED_COMM', $allowed_comm );
	$xtpl->assign( 'BASE_URL_COMM', $base_url );
	$xtpl->assign( 'URL_COMMENT', $url_comment );

	// Order by comm
	for( $i = 0; $i <= 2; ++$i )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $i,
			'title' => $lang_module_comment['sortcomm_' . $i],
			'selected' => ( $i == $sortcomm ) ? ' selected="selected"' : '',
			) );

		$xtpl->parse( 'main.sortcomm' );
	}

	$xtpl->assign( 'COMMENTCONTENT', $comment );
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
			$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
			$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME );
			$xtpl->parse( 'main.allowed_comm.captcha' );
		}
		else
		{
			$xtpl->assign( 'GFX_NUM', 0 );
		}

		$xtpl->parse( 'main.allowed_comm' );
	}
	elseif( $form_login )
	{
		$link_login = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&amp;nv_redirect=' . nv_base64_encode( $client_info['selfurl'] . '#formcomment' );
		$xtpl->assign( 'COMMENT_LOGIN', '<a title="' . $lang_global['loginsubmit'] . '" href="' . $link_login . '">' . $lang_module_comment['comment_login'] . '</a>' );
		$xtpl->parse( 'main.form_login' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_comment_module_data( $module, $comment_array, $is_delete )
{
	global $global_config, $module_info, $module_file, $module_config, $lang_module_comment;

	$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/comment' );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'LANG', $lang_module_comment );

	if( ! empty( $comment_array['comment'] ) )
	{
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
			else
			{
				$comment_array_i['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.jpg';
			}
			if( ! empty ($comment_array_i['userid']) )
			{
				$comment_array_i['post_name'] = ( $global_config['name_show'] )  ? $comment_array_i['first_name'] . ' ' . $comment_array_i['last_name'] : $comment_array_i['last_name'] . ' ' . $comment_array_i['first_name'];
				$comment_array_i['post_name'] = trim( $comment_array_i['post_name'] );
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
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_comment_module_data_reply( $module, $comment_array, $is_delete )
{
	global $global_config, $module_info, $module_file, $module_config, $lang_module_comment;

	$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/comment' );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
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
			$comment_array_i['photo'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.jpg';
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