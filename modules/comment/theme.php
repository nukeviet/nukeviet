<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if( ! defined( 'NV_IS_MOD_COMMENT' ) ) die( 'Stop!!!' );

/**
 * nv_theme_comment_main()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_comment_main( $module, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $base_url, $form_login )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $admin_info, $user_info, $lang_global, $client_info;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );

	$xtpl->assign( 'MODULE_COMM', $module );
	$xtpl->assign( 'AREA_COMM', $area );
	$xtpl->assign( 'ID_COMM', $id );
	$xtpl->assign( 'ALLOWED_COMM', $allowed_comm );
	$xtpl->assign( 'CHECKSS_COMM', $checkss );
	$xtpl->assign( 'BASE_URL_COMM', $base_url );

	// Order by comm
	for( $i = 0; $i <= 2; ++$i )
	{
		$xtpl->assign( 'OPTION', array(
			'key' => $i,
			'title' => $lang_module['sortcomm_' . $i],
			'selected' => ( $i == $sortcomm ) ?  ' selected="selected"' : '',
		) );

		$xtpl->parse( 'main.sortcomm' );
	}

	$xtpl->assign( 'COMMENTCONTENT', $comment );

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
			$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha' );
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
		$xtpl->assign( 'COMMENT_LOGIN', '<a title="' . $lang_global['loginsubmit'] . '" href="' . $link_login . '">' . $lang_module['comment_login'] . '</a>' );
		$xtpl->parse( 'main.form_login' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_comment_theme( $module, $comment_array, $is_delete )
{
	global $global_config, $module_info, $module_name, $module_file, $module_config, $lang_module;

	$xtpl = new XTemplate( 'comment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'LANG', $lang_module );

	foreach( $comment_array['comment'] as $comment_array_i )
	{
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