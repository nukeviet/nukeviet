<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( $global_config['allowuserlogin'] and defined( 'NV_OPENID_ALLOWED' ) )
{
	$nv_redirect = $nv_Request->get_title( 'nv_redirect', 'post,get', '' );
	$server = $nv_Request->get_string( 'server', 'get', '' );
	if( ! empty( $server ) and in_array( $server, $global_config['openid_servers'] ) )
	{
		if( file_exists(NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php') )
		{
			include NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php';
		}
		elseif( file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php') )
		{
			include NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php';
		}
		else
		{
			$openid = new LightOpenID();

			if( $nv_Request->isset_request( 'openid_mode', 'get' ) )
			{
				$openid_mode = $nv_Request->get_string( 'openid_mode', 'get', '' );

				if( $openid_mode == 'cancel' )
				{
					$attribs = array( 'result' => 'cancel' );
				}
				elseif( ! $openid->validate() )
				{
					$attribs = array( 'result' => 'notlogin' );
				}
				else
				{
					$attribs = array(
						'result' => 'is_res',
						'id' => $openid->identity,
						'server' => $server
					) + $openid->getAttributes();
				}

				$attribs = serialize( $attribs );
				$nv_Request->set_Session( 'openid_attribs', $attribs );

				$op_redirect = ( defined( 'NV_IS_USER' ) ) ? 'openid' : 'login';
				Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1&nv_redirect=' . $nv_redirect );
				exit();
			}

			if( ! $nv_Request->isset_request( 'result', 'get' ) )
			{
				include_once NV_ROOTDIR . '/modules/users/login/openid-' . $server . '.php' ;
				$openid->identity = $openid_server_config['identity'];
				$openid->required = array_values( $openid_server_config['required'] );
				header( 'Location: ' . $openid->authUrl() );
				die();
			}
			exit();
		}
	}
}

Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
die();