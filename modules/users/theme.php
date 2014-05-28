<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

/**
 * user_register()
 *
 * @param mixed $gfx_chk
 * @param mixed $array_register
 * @param mixed $siteterms
 * @param mixed $data_questions
 * @return
 */
function user_register( $gfx_chk, $array_register, $siteterms, $data_questions, $array_field_config, $custom_fields )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $nv_Request;
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";

	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
					$('#registerForm').validate({
					});
				 });";
	$my_head .= " </script>\n";

	$xtpl = new XTemplate( 'register.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register' );
	$xtpl->assign( 'NICK_MAXLENGTH', NV_UNICKMAX );
	$xtpl->assign( 'PASS_MAXLENGTH', NV_UPASSMAX );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_register );
	$xtpl->assign( 'NV_SITETERMS', $siteterms );
	foreach( $data_questions as $array_question_i )
	{
		$xtpl->assign( 'QUESTIONVALUE', $array_question_i );
		$xtpl->parse( 'main.frquestion' );
	}

	if( ! empty( $array_field_config ) )
	{
		$a = 0;
		$userid = 0;
		foreach( $array_field_config as $row )
		{
			if( ( $row['show_register'] and $userid == 0 ) or $userid > 0 )
			{
				$row['tbodyclass'] = ( $a % 2 ) ? " class=\"second\"" : "";
				if( $userid == 0 and empty( $custom_fields ) )
				{
					if( ! empty( $row['field_choices'] ) )
					{
						if( $row['field_type'] == 'date' )
						{
							$row['value'] = ( $row['field_choices']['current_date'] ) ? NV_CURRENTTIME : $row['default_value'];
						}
						elseif( $row['field_type'] == 'number' )
						{
							$row['value'] = $row['default_value'];
						}
						else
						{
							$temp = array_keys( $row['field_choices'] );
							$tempkey = intval( $row['default_value'] ) - 1;
							$row['value'] = ( isset( $temp[$tempkey] ) ) ? $temp[$tempkey] : '';
						}
					}
					else
					{
						$row['value'] = $row['default_value'];
					}
				}
				else
				{
					$row['value'] = ( isset( $custom_fields[$row['field']] ) ) ? $custom_fields[$row['field']] : $row['default_value'];
				}
				$row['required'] = ( $row['required'] ) ? 'required' : '';

				$xtpl->assign( 'FIELD', $row );
				if( $row['required'] )
				{
					$xtpl->parse( 'main.field.loop.required' );
				}
				if( $row['field_type'] == 'textbox' or $row['field_type'] == 'number' )
				{
					$xtpl->parse( 'main.field.loop.textbox' );
				}
				elseif( $row['field_type'] == 'date' )
				{
					$row['value'] = ( empty( $row['value'] ) ) ? '' : date( 'd/m/Y', $row['value'] );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.field.loop.date' );
				}
				elseif( $row['field_type'] == 'textarea' )
				{
					$row['value'] = nv_htmlspecialchars( nv_br2nl( $row['value'] ) );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.field.loop.textarea' );
				}
				elseif( $row['field_type'] == 'editor' )
				{
					$row['value'] = htmlspecialchars( nv_editor_br2nl( $row['value'] ) );
					if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
					{
						$array_tmp = explode( "@", $row['class'] );
						$edits = nv_aleditor( 'custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'] );
						$xtpl->assign( 'EDITOR', $edits );
						$xtpl->parse( 'main.field.loop.editor' );
					}
					else
					{
						$row['class'] = '';
						$xtpl->assign( 'FIELD', $row );
						$xtpl->parse( 'main.field.loop.textarea' );
					}
				}
				elseif( $row['field_type'] == 'select' )
				{
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( $key == $row['value'] ) ? ' selected="selected"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.select.loop' );
					}
					$xtpl->parse( 'main.field.loop.select' );
				}
				elseif( $row['field_type'] == 'radio' )
				{
					$number = 0;
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( $key == $row['value'] ) ? ' checked="checked"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.radio' );
					}
				}
				elseif( $row['field_type'] == 'checkbox' )
				{
					$number = 0;
					$valuecheckbox = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( in_array( $key, $valuecheckbox ) ) ? ' checked="checked"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.checkbox' );
					}
				}
				elseif( $row['field_type'] == 'multiselect' )
				{
					$valueselect = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( in_array( $key, $valueselect ) ) ? ' selected="selected"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.multiselect.loop' );
					}
					$xtpl->parse( 'main.field.loop.multiselect' );
				}
				$xtpl->parse( 'main.field.loop' );
			}
		}
		$xtpl->parse( 'main.field' );
	}

	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.captcha' );
	}

	if( $global_config['allowuserreg'] == 2 )
	{
		$xtpl->assign( 'LOSTACTIVELINK_SRC', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink' );
		$xtpl->parse( 'main.lostactivelink' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * openid_register()
 *
 * @param mixed $gfx_chk
 * @param mixed $array_register
 * @param mixed $siteterms
 * @param mixed $data_questions
 * @return
 */
function openid_register( $gfx_chk, $array_register, $siteterms, $data_questions )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $nv_redirect;

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#registerForm').validate({
			});
		 });";
	$my_head .= " </script>\n";

	$xtpl = new XTemplate( 'openid_register.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register&amp;openid=1' );
	$xtpl->assign( 'NICK_MAXLENGTH', NV_UNICKMAX );
	$xtpl->assign( 'PASS_MAXLENGTH', NV_UPASSMAX );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_register );
	$xtpl->assign( 'NV_SITETERMS', $siteterms );

	foreach( $data_questions as $array_question_i )
	{
		$xtpl->assign( 'QUESTIONVALUE', $array_question_i );
		$xtpl->parse( 'main.frquestion' );
	}

	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.captcha' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_login()
 *
 * @param mixed $gfx_chk
 * @param mixed $array_login
 * @return
 */
function user_login( $gfx_chk, $array_login )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $openid_servers;

	$xtpl = new XTemplate( 'login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users' );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#loginForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login' );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register' );
	$xtpl->assign( 'USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_login );

	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.captcha' );
	}

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
		$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
		$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );

		$assigns = array();
		foreach( $openid_servers as $server => $value )
		{
			$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $server;
			$assigns['title'] = ucfirst( $server );
			$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
			$assigns['img_width'] = $assigns['img_height'] = 24;

			$xtpl->assign( 'OPENID', $assigns );
			$xtpl->parse( 'main.openid.server' );
		}

		$xtpl->parse( 'main.openid' );
	}

	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}

/**
 * user_openid_login()
 *
 * @param mixed $gfx_chk
 * @param mixed $array_login
 * @param mixed $attribs
 * @return
 */
function user_openid_login( $gfx_chk, $array_login, $attribs )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $openid_servers;

	$xtpl = new XTemplate( 'openid_login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users' );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#loginForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1&amp;option=3' );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register' );
	$xtpl->assign( 'USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass' );
	$xtpl->assign( 'NICK_MAXLENGTH', NV_UNICKMAX );
	$xtpl->assign( 'PASS_MAXLENGTH', NV_UPASSMAX );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );

	$assigns = array();
	foreach( $openid_servers as $server => $value )
	{
		$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $server;
		$assigns['title'] = ucfirst( $server );
		$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
		$assigns['img_width'] = $assigns['img_height'] = 24;

		$xtpl->assign( 'OPENID', $assigns );
		$xtpl->parse( 'main.server' );
	}

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_login );

	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.captcha' );
	}

	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}

/**
 * user_openid_login2()
 *
 * @param mixed $attribs
 * @param mixed $array_user_login
 * @return
 */
function user_openid_login2( $attribs, $array_user_login )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $openid_servers, $nv_redirect;

	$xtpl = new XTemplate( 'openid_login2.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/users' );

	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register&amp;nv_redirect=' . $nv_redirect );
	$xtpl->assign( 'USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass&amp;nv_redirect=' . $nv_redirect );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );

	foreach( $array_user_login as $value )
	{
		$xtpl->assign( 'USER_LOGIN', $value );
		$xtpl->parse( 'main.login_note' );
	}

	$assigns = array();
	foreach( $openid_servers as $server => $value )
	{
		$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $server . "&amp;nv_redirect=" . $nv_redirect;
		$assigns['title'] = ucfirst( $server );
		$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
		$assigns['img_width'] = $assigns['img_height'] = 24;

		$xtpl->assign( 'OPENID', $assigns );
		$xtpl->parse( 'main.server' );
	}

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}

/**
 * user_lostpass()
 *
 * @param mixed $data
 * @param mixed $question
 * @return
 */
function user_lostpass( $data, $question )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$xtpl = new XTemplate( 'lostpass.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#lostpassForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $data );

	if( $data['step'] == 2 )
	{
		$xtpl->assign( 'FORM2_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass' );
		$xtpl->assign( 'QUESTION', $question );
		$xtpl->parse( 'main.step2' );
	}
	else
	{
		$xtpl->assign( 'FORM1_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass' );
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.step1' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_lostactivelink()
 *
 * @param mixed $data
 * @param mixed $question
 * @return
 */
function user_lostactivelink( $data, $question )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$xtpl = new XTemplate( 'lostactivelink.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#lostpassForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $data );

	if( $data['step'] == 2 )
	{
		$xtpl->assign( 'FORM2_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink' );
		$xtpl->assign( 'QUESTION', $question );
		$xtpl->parse( 'main.step2' );
	}
	else
	{
		$xtpl->assign( 'FORM1_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostactivelink' );
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.step1' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_changepass()
 *
 * @param mixed $array_data
 * @return
 */
function user_changepass( $array_data = array() )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$groups_list = nv_groups_list_pub();

	$xtpl = new XTemplate( 'changepass.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#changePassForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'USER_CHANGEPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=changepass' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_data );
	$xtpl->assign( 'PASS_MAXLENGTH', NV_UPASSMAX );

	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}

	if( ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.logout' );
	}
	if( ! empty( $groups_list ) && $global_config['allowuserpublic'] == 1 )
	{
		$xtpl->parse( 'main.regroups' );
	}

	if( ! $array_data['pass_empty'] )
	{
		$xtpl->parse( 'main.passEmpty' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_changequestion()
 *
 * @param mixed $array_data
 * @return
 */
function user_changequestion( $array_data )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$groups_list = nv_groups_list_pub();

	$xtpl = new XTemplate( 'changequestion.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#changeQuestionForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}
	if( ! empty( $groups_list ) && $global_config['allowuserpublic'] == 1 )
	{
		$xtpl->parse( 'main.regroups' );
	}
	if( ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.logout' );
	}

	$xtpl->assign( 'DATA', $array_data );

	if( $array_data['step'] == 2 )
	{
		$xtpl->assign( 'FORM2_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo&amp;changequestion=1' );
		foreach( $array_data['questions'] as $key => $question )
		{
			$xtpl->assign( 'QUESTIONVALUE', $key );
			$xtpl->assign( 'QUESTIONTITLE', $question );
			$xtpl->parse( 'main.step2.frquestion' );
		}
		$xtpl->parse( 'main.step2' );
	}
	else
	{
		$xtpl->assign( 'FORM1_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo&amp;changequestion=1' );
		$xtpl->assign( 'PASS_MAXLENGTH', NV_UPASSMAX );
		$xtpl->parse( 'main.step1' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_info()
 *
 * @param mixed $data
 * @return
 */
function user_info( $data, $array_field_config, $custom_fields, $error )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$groups_list = nv_groups_list_pub();

	$xtpl = new XTemplate( 'info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'EDITINFO_FORM', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo' );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'LANG', $lang_module );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}

	if( ! empty( $groups_list ) && $global_config['allowuserpublic'] == 1 )
	{
		$xtpl->parse( 'main.regroups' );
	}

	if( ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.logout' );
	}

	$xtpl->assign( 'DATA', $data );

	if( $data['allowloginchange'] )
	{
		$xtpl->assign( 'NICK_MAXLENGTH', NV_UNICKMAX );
		$xtpl->parse( 'main.username_change' );
	}
	else
	{
		$xtpl->parse( 'main.username_no_change' );
	}

	if( $data['allowmailchange'] )
	{
		$xtpl->parse( 'main.email_change' );
	}
	else
	{
		$xtpl->parse( 'main.email_no_change' );
	}

	foreach( $data['gender_array'] as $gender )
	{
		$xtpl->assign( 'GENDER', $gender );
		$xtpl->parse( 'main.gender_option' );
	}

	// Parse photo
	if( ! empty( $data['photo'] ) )
	{
		$xtpl->parse( 'main.photo' );
	}
	else
	{
		$xtpl->parse( 'main.add_photo' );
	}

	// Parse custom fields
	if( ! empty( $array_field_config ) )
	{
		$a = 0;
		$userid = 0;
		foreach( $array_field_config as $row )
		{
			if( ( $row['show_register'] and $userid == 0 ) or $userid > 0 )
			{
				$row['tbodyclass'] = ( $a % 2 ) ? " class=\"second\"" : "";

				if( $userid == 0 and empty( $custom_fields ) )
				{
					if( ! empty( $row['field_choices'] ) )
					{
						if( $row['field_type'] == 'date' )
						{
							$row['value'] = ( $row['field_choices']['current_date'] ) ? NV_CURRENTTIME : $row['default_value'];
						}
						elseif( $row['field_type'] == 'number' )
						{
							$row['value'] = $row['default_value'];
						}
						else
						{
							$temp = array_keys( $row['field_choices'] );
							$tempkey = intval( $row['default_value'] ) - 1;
							$row['value'] = ( isset( $temp[$tempkey] ) ) ? $temp[$tempkey] : '';
						}
					}
					else
					{
						$row['value'] = $row['default_value'];
					}
				}
				else
				{
					$row['value'] = ( isset( $custom_fields[$row['field']] ) ) ? $custom_fields[$row['field']] : $row['default_value'];
				}

				$row['required'] = ( $row['required'] ) ? 'required' : '';

				$xtpl->assign( 'FIELD', $row );

				if( $row['required'] )
				{
					$xtpl->parse( 'main.field.loop.required' );
				}

				if( $row['field_type'] == 'textbox' or $row['field_type'] == 'number' )
				{
					$xtpl->parse( 'main.field.loop.textbox' );
				}
				elseif( $row['field_type'] == 'date' )
				{
					$row['value'] = ( empty( $row['value'] ) ) ? '' : date( 'd/m/Y', $row['value'] );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.field.loop.date' );
				}
				elseif( $row['field_type'] == 'textarea' )
				{
					$row['value'] = nv_htmlspecialchars( nv_br2nl( $row['value'] ) );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.field.loop.textarea' );
				}
				elseif( $row['field_type'] == 'editor' )
				{
					$row['value'] = htmlspecialchars( nv_editor_br2nl( $row['value'] ) );
					if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
					{
						$array_tmp = explode( "@", $row['class'] );
						$edits = nv_aleditor( 'custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'] );
						$xtpl->assign( 'EDITOR', $edits );
						$xtpl->parse( 'main.field.loop.editor' );
					}
					else
					{
						$row['class'] = '';
						$xtpl->assign( 'FIELD', $row );
						$xtpl->parse( 'main.field.loop.textarea' );
					}
				}
				elseif( $row['field_type'] == 'select' )
				{
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( $key == $row['value'] ) ? ' selected="selected"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.select.loop' );
					}
					$xtpl->parse( 'main.field.loop.select' );
				}
				elseif( $row['field_type'] == 'radio' )
				{
					$number = 0;
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( $key == $row['value'] ) ? ' checked="checked"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.radio' );
					}
				}
				elseif( $row['field_type'] == 'checkbox' )
				{
					$number = 0;
					$valuecheckbox = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();

					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( in_array( $key, $valuecheckbox ) ) ? ' checked="checked"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.checkbox' );
					}
				}
				elseif( $row['field_type'] == 'multiselect' )
				{
					$valueselect = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();

					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( in_array( $key, $valueselect ) ) ? ' selected="selected"' : '',
							"value" => $value
						) );
						$xtpl->parse( 'main.field.loop.multiselect.loop' );
					}
					$xtpl->parse( 'main.field.loop.multiselect' );
				}
				$xtpl->parse( 'main.field.loop' );
			}
		}
		$xtpl->parse( 'main.field' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_welcome()
 *
 * @return
 */
function user_welcome()
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head, $user_info, $lang_global;

	$xtpl = new XTemplate( 'userinfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	$groups_list = nv_groups_list_pub();

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}

	if( ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.logout' );
	}
	if( ( ! empty( $groups_list ) ) && ( $global_config['allowuserpublic'] == 1 ) )
	{
		$xtpl->parse( 'main.regroups' );
	}

	if( ! empty( $user_info['photo'] ) and file_exists( NV_ROOTDIR . '/' . $user_info['photo'] ) )
	{
		$xtpl->assign( 'SRC_IMG', NV_BASE_SITEURL . $user_info['photo'] );
	}
	else
	{
		$xtpl->assign( 'SRC_IMG', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_avatar.jpg' );
	}

	$user_info['gender'] = ( $user_info['gender'] == "M" ) ? $lang_module['male'] : ( $user_info['gender'] == 'F' ? $lang_module['female'] : $lang_module['na'] );
	$user_info['birthday'] = empty( $user_info['birthday'] ) ? $lang_module['na'] : nv_date( 'd/m/Y', $user_info['birthday'] );
	$user_info['regdate'] = nv_date( 'd/m/Y', $user_info['regdate'] );
	$user_info['view_mail'] = empty( $user_info['view_mail'] ) ? $lang_module['no'] : $lang_module['yes'];
	$user_info['last_login'] = empty( $user_info['last_login'] ) ? '' : nv_date( 'l, d/m/Y H:i', $user_info['last_login'] );
	$user_info['current_login'] = nv_date( 'l, d/m/Y H:i', $user_info['current_login'] );
	$user_info['st_login'] = ! empty( $user_info['st_login'] ) ? $lang_module['yes'] : $lang_module['no'];

	if( isset( $user_info['current_mode'] ) and $user_info['current_mode'] == 3 )
	{
		$user_info['current_mode'] = $lang_module['admin_login'];
	}
	elseif( isset( $user_info['current_mode'] ) and $user_info['current_mode'] == 2 )
	{
		$user_info['current_mode'] = $lang_module['openid_login'] . ': ' . $user_info['openid_server'] . ' (' . $user_info['openid_email'] . ')';
	}
	else
	{
		$user_info['current_mode'] = $lang_module['st_login'];
	}

	$user_info['change_name_info'] = sprintf( $lang_module['change_name_info'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=editinfo" );
	$user_info['pass_empty_note'] = sprintf( $lang_module['pass_empty_note'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=changepass" );
	$user_info['question_empty_note'] = sprintf( $lang_module['question_empty_note'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=editinfo&amp;changequestion" );

	$xtpl->assign( 'USER', $user_info );

	if( ! $global_config['allowloginchange'] and ! empty( $user_info['current_openid'] ) and empty( $user_info['last_login'] ) and empty( $user_info['last_agent'] ) and empty( $user_info['last_ip'] ) and empty( $user_info['last_openid'] ) )
	{
		$xtpl->parse( 'main.change_login_note' );
	}

	if( $user_info['st_login'] == $lang_module['no'] )
	{
		$xtpl->parse( 'main.pass_empty_note' );
	}

	if( empty( $user_info['valid_question'] ) )
	{
		$xtpl->parse( 'main.question_empty_note' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_info_exit()
 *
 * @param mixed $info
 * @return
 */
function user_info_exit( $info )
{
	global $module_info, $module_file;

	$xtpl = new XTemplate( 'info_exit.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'INFO', $info );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * openid_account_confirm()
 *
 * @param mixed $gfx_chk
 * @param mixed $attribs
 * @return
 */
function openid_account_confirm( $gfx_chk, $attribs )
{
	global $my_head, $lang_global, $lang_module, $module_info, $module_file, $module_name, $openid_servers, $nv_redirect;

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#loginForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl = new XTemplate( 'confirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'OPENID_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1' );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );
	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->parse( 'main.captcha' );
	}

	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;nv_redirect=' . $nv_redirect );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register&amp;nv_redirect=' . $nv_redirect );

	$assigns = array();
	foreach( $openid_servers as $server => $value )
	{
		$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $server;
		$assigns['title'] = ucfirst( $server );
		$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
		$assigns['img_width'] = $assigns['img_height'] = 24;

		$xtpl->assign( 'OPENID', $assigns );
		$xtpl->parse( 'main.server' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * openid_active_confirm()
 *
 * @param mixed $gfx_chk
 * @param mixed $attribs
 * @return
 */
function openid_active_confirm( $gfx_chk, $attribs )
{
	global $my_head, $lang_global, $lang_module, $module_info, $module_file, $module_name, $openid_servers;

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.min.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.validator-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "$(document).ready(function(){
			$('#loginForm').validate();
		 });";
	$my_head .= " </script>\n";

	$xtpl = new XTemplate( 'active_confirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'OPENID_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1' );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );
	if( $gfx_chk )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . '?scaptcha=captcha' );
		$xtpl->parse( 'main.captcha' );
	}
	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login' );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register' );

	$assigns = array();
	foreach( $openid_servers as $server => $value )
	{
		$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=login&amp;server=" . $server;
		$assigns['title'] = ucfirst( $server );
		$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
		$assigns['img_width'] = $assigns['img_height'] = 24;

		$xtpl->assign( 'OPENID', $assigns );
		$xtpl->parse( 'main.server' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_openid_administrator()
 *
 * @param mixed $data
 * @return
 */
function user_openid_administrator( $data )
{
	global $my_head, $lang_global, $lang_module, $module_info, $module_file, $module_name, $global_config, $openid_servers;

	$groups_list = nv_groups_list_pub();

	$xtpl = new XTemplate( 'openid_administrator.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/openid.gif' );
	$xtpl->assign( 'OPENID_IMG_WIDTH', 150 );
	$xtpl->assign( 'OPENID_IMG_HEIGHT', 60 );

	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( defined( 'NV_IS_USER_FORUM' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}
	if( ! empty( $groups_list ) && $global_config['allowuserpublic'] == 1 )
	{
		$xtpl->parse( 'main.regroups' );
	}
	if( ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.logout' );
	}

	$xtpl->assign( 'DATA', $data );

	if( ! empty( $data['openid_list'] ) )
	{
		$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=openid&amp;del=1' );

		foreach( $data['openid_list'] as $key => $openid_list )
		{
			if( $key % 2 == 0 )
			{
				$xtpl->assign( 'OPENID_CLASS', ' gray' );
			}
			else
			{
				$xtpl->assign( 'OPENID_CLASS', '' );
			}
			$xtpl->assign( 'OPENID_LIST', $openid_list );
			$xtpl->parse( 'main.openid_empty.openid_list' );
		}
		$xtpl->parse( 'main.openid_empty' );
	}

	$assigns = array();
	foreach( $openid_servers as $server => $value )
	{
		$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=openid&amp;server=" . $server;
		$assigns['title'] = ucfirst( $server );
		$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $server . ".gif";
		$assigns['img_width'] = $assigns['img_height'] = 24;

		$xtpl->assign( 'OPENID', $assigns );
		$xtpl->parse( 'main.server' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_regroup_theme()
 *
 * @param mixed $groups
 * @return
 */
function nv_regroup_theme( $groups )
{
	global $module_info, $module_file, $module_name, $lang_module;
	$xtpl = new XTemplate( 're_groups.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );
	$xtpl->assign( 'LANG', $lang_module );

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->parse( 'main.allowopenid' );
	}

	foreach( $groups as $group )
	{
		$xtpl->assign( 'GROUP', $group );
		$xtpl->parse( 'main.list' );
	}

	if( ! defined( 'NV_IS_ADMIN' ) ) $xtpl->parse( 'main.logout' );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_memberslist_theme()
 *
 * @param mixed $users_array
 * @param mixed $array_order_new
 * @param mixed $generate_page
 * @return
 */
function nv_memberslist_theme( $users_array, $array_order_new, $generate_page )
{
	global $module_info, $module_file, $lang_module;

	$xtpl = new XTemplate( 'memberslist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	foreach( $array_order_new as $key => $link )
	{
		$xtpl->assign( $key, $link );
	}

	foreach( $users_array as $user )
	{
		$xtpl->assign( 'USER', $user );

		if( ! empty( $user['full_name'] ) && $user['full_name'] != $user['username'] )
		{
			$xtpl->parse( 'main.list.fullname' );
		}
		$xtpl->parse( 'main.list' );
	}

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_memberslist_detail_theme()
 *
 * @param mixed $item
 * @return
 */
function nv_memberslist_detail_theme( $item, $array_field_config, $custom_fields )
{
	global $module_info, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'viewdetailusers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'URL_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( ! empty( $item['photo'] ) and file_exists( NV_ROOTDIR . '/' . $item['photo'] ) )
	{
		$xtpl->assign( 'SRC_IMG', NV_BASE_SITEURL . $item['photo'] );
	}
	else
	{
		$xtpl->assign( 'SRC_IMG', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_avatar.jpg' );
	}

	$item['gender'] = ( $item['gender'] == "M" ) ? $lang_module['male'] : ( $item['gender'] == 'F' ? $lang_module['female'] : $lang_module['na'] );
	$item['birthday'] = empty( $item['birthday'] ) ? $lang_module['na'] : nv_date( 'd/m/Y', $item['birthday'] );
	$item['regdate'] = nv_date( 'd/m/Y', $item['regdate'] );
	$item['last_login'] = empty( $item['last_login'] ) ? '' : nv_date( 'l, d/m/Y H:i', $item['last_login'] );

	$xtpl->assign( 'USER', $item );

	if( ! empty( $item['view_mail'] ) )
	{
		$xtpl->parse( 'main.viewemail' );
	}

	// Parse custom fields
	if( ! empty( $array_field_config ) )
	{
		//var_dump($array_field_config); die();
		foreach( $array_field_config as $row )
		{
			if( $row['show_profile'] )
			{
				$question_type = $row['field_type'];
				if( $question_type == 'checkbox' )
				{
					$result = explode( ',', $custom_fields[$row['field']] );
					$value = '';
					foreach( $result as $item )
					{
						$value .= $row['field_choices'][$item] . '<br />';
					}
				}
				elseif( $question_type == 'multiselect' or $question_type == 'select' or $question_type == 'radio' )
				{
					$value = $row['field_choices'][$custom_fields[$row['field']]];
				}
				else
				{
					$value = $custom_fields[$row['field']];
				}
				$xtpl->assign( 'FIELD', array( 'title' => $row['title'], 'value' => $value ) );
				$xtpl->parse( 'main.field.loop' );
			}
		}
		$xtpl->parse( 'main.field' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * user_info_exit_redirect()
 *
 * @param mixed $info
 * @param mixed $nv_redirect
 * @return void
 */
function user_info_exit_redirect( $info, $nv_redirect )
{
	global $module_info, $module_file, $lang_module;

	$xtpl = new XTemplate( 'info_exit_redirect.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'INFO', $info );
	$xtpl->assign( 'NV_REDIRECT', $nv_redirect );

	$xtpl->parse( 'main' );

	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

/**
 * nv_avatar()
 *
 * @param mixed $array
 * @return void
 */
function nv_avatar( $array )
{
	global $module_info, $module_file, $module_name, $lang_module, $global_config, $my_head;

	// Include JS and CSS
	$my_head .= "<script src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.Jcrop.min.js\" type=\"text/javascript\"></script>" . NV_EOL;
	$my_head .= "<link href=\"" . NV_BASE_SITEURL . "js/jquery/jquery.Jcrop.min.css\" rel=\"stylesheet\" type=\"text/css\" />" . NV_EOL;

	$xtpl = new XTemplate( 'avatar.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );

	$xtpl->assign( 'NV_AVATAR_WIDTH', $global_config['avatar_width'] );
	$xtpl->assign( 'NV_AVATAR_HEIGHT', $global_config['avatar_height'] );
	$xtpl->assign( 'NV_MAX_WIDTH', NV_MAX_WIDTH );
	$xtpl->assign( 'NV_MAX_HEIGHT', NV_MAX_HEIGHT );
	$xtpl->assign( 'NV_UPLOAD_MAX_FILESIZE', NV_UPLOAD_MAX_FILESIZE );
	$xtpl->assign( 'NV_AVATAR_UPLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar' );

	$lang_module['avata_bigfile'] = sprintf( $lang_module['avata_bigfile'], nv_convertfromBytes( NV_UPLOAD_MAX_FILESIZE ) );
	$lang_module['avata_bigsize'] = sprintf( $lang_module['avata_bigsize'], NV_MAX_WIDTH, NV_MAX_HEIGHT );
	$lang_module['avata_smallsize'] = sprintf( $lang_module['avata_smallsize'], $global_config['avatar_width'], $global_config['avatar_height'] );

	$xtpl->assign( 'LANG', $lang_module );

	if( $array['error'] )
	{
		$xtpl->assign( 'ERROR', $array['error'] );
		$xtpl->parse( 'main.error' );
	}
	if( ! $array['success'] )
	{
		$xtpl->parse( 'main.init' );
	}
	else
	{
		$xtpl->assign( 'FILENAME', $array['filename'] );
		$xtpl->parse( 'main.complete' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}